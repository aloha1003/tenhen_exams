<?php
namespace App\Service;
use App\Models\LotteryChannel;
use App\Models\Lottery;
use Log;
class LotteryService {

    public function __construct($lottery = [])
    {
        $this->lottery = $lottery;
    }

    public function getWinningNumber()
    {   
        //從db取得可用的彩票結果來源
        $instancesMap = app(LotteryChannel::class)->all();   
        $instances = $this->getInstances($instancesMap);    
        $mainInstance = '\App\LotteryInstance\\'.$instances['mainInstanceName'];

        if (!class_exists($mainInstance)) {
            throw new \Exception("Not found 主彩源", 1);
        }
        
        $mainInstance = app($mainInstance, ['lottery' => $this->lottery]);
        $mainResult = $mainInstance->getWinningNumber();
        if (!$mainResult) {
            throw new \Exception("Not found 主彩源還沒有資料", 1);
        }
        $resultIsOk = false;
        
        foreach ($instances['subInstaceNameList'] as $key => $instanceName) {
            $instance = '\App\LotteryInstance\\'.$instanceName;
            if (!class_exists($instance)) {
                Log::info('Log message', array('context' => '找不到副彩源:'.$instanceName));
                continue;
            }
            $subInstance = app($instance, ['lottery' => $this->lottery]);
            $subResult = $subInstance->getWinningNumber();
            if (!$subResult) {
                Log::error('Not found 副彩源'.$instanceName.'還沒有'.$this->lottery->issue.'的資料。');
                continue;
            }
            //比对结果
            if ($subResult['numbers'] === $mainResult['numbers']) {
                $resultIsOk = true;
                break;
            }
            
        }
        if ($resultIsOk) {
            return $mainResult['numbers'];
        } else {
            throw new \Exception("主彩源無法比對任何副彩源", 1);
        }
    }
    
    /**
     * 返回彩源
     * @param  [type] $instancesMap [description]
     * @return [type]               [description]
     */
    public function getInstances($instancesMap)
    {
        $mainInstanceName = null;
        $subInstaceNameList = [];
        foreach ($instancesMap as $key => $instance) {
            if ($instance->master_game_id == $this->lottery->game_id) { //如果指定的彩種跟當入的彩種一致的話，就設為主要彩源
                $mainInstanceName = $instance->slug;
            } else {
                $subInstaceNameList[] = $instance->slug;
            }    
        }
        return compact('mainInstanceName', 'subInstaceNameList');
    }
}

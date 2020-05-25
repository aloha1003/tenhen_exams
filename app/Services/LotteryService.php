<?php
namespace App\Service;
use App\Models\LotteryChannel;
class LotteryService {

    public function __construct(Lottery $lottery)
    {
        $this->lottery = $lottery;
    }

    public function getWinningNumber()
    {   
        //從db取得可用的彩票結果來源
        $instancesMap = app(LotteryChannel::class)->all();   
        $instances = $this->getInstances($instancesMap);    
        $mainInstance = '\App\LotteryInstance\\'.$instances['mainInstanceName'];

        if (class_exists())
        $mainInstance = app(\App\LotteryInstance\OneFakeLottery::class, ['lottery' => $this->lottery]);
        $mainResult = $mainInstance->getWinningNumber();
        //
        $resultIsOk = false;
        
        foreach ($instancesMap as $key => $instanceName) {
            $subInstance = app($instanceName, ['lottery' => $this->lottery]);
            $subResult = $subInstance->getWinningNumber();

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
            if ($instance->slug == $this->lottery->gameId) { //如果指定的彩種跟當入的彩種一致的話，就設為主要彩源
                $mainInstanceName = $instance->slug;
            } else {
                $subInstaceNameList[] = $instance->slug;
            }    
        }
        return compact('mainInstanceName', 'subInstaceNameList');
    }
}

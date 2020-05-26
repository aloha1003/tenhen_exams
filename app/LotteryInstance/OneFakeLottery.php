<?php
namespace App\LotteryInstance;
use App\LotteryInstance\BaseLotteryCapturer;
use App\Models\LotteryResult;
use App\Models\Lottery;

class OneFakeLottery extends BaseLotteryCapturer  {
    protected $gameKeyMap = [
        self::KIND_CHONGQINGSHISHICAI => 'ssc',
        self::KIND_BEIJING => 'bjsyxw',
    ]; 
    protected $resultColumnMapping = [
        'issue_at' => 'gid',
        'numbers' => 'award',
    ];  
    public function __construct(Lottery $lottery)
    {
        $this->lottery = $lottery;
    }
    /**
     * [getWinningNumber description]
     * @return [type] [description]
     */
    public function getWinningNumber()
    {

        $gameKey = $this->gameKeyMap[$this->lottery->game_id] ?? '';
        if (!$gameKey) {
            throw new \Exception("Not found correct gameKey");
        }
        $urlParams = ['gamekey' => $gameKey, 'issue' => $this->lottery->issue];
        $url = 'http://one.fake/v1?'.http_build_query($urlParams);
        
        $ch = curl_init();
        $options = [
                 CURLOPT_URL => $url,
                 CURLOPT_HEADER => false,
                 CURLOPT_POST => false ,
                 CURLOPT_RETURNTRANSFER => true,
                ];

        curl_setopt_array($ch, $options);
        
        $output = curl_exec($ch); 
        curl_close($ch);
        $this->validCurlResult($output);
        $result = json_decode($output, true);
        
        $data = $result['result']['data'] ;

        $formatResult = $this->formatResult($data);

        return $formatResult[$this->lottery->issue] ?? '';
    }

    

    protected function validCurlResult($curlResult)
    {
        $result = json_decode($curlResult, true);
        if (json_last_error()){
            throw new \Exception("Illegal format result:".$curlResult); 
        }

      // correct json format
      //  {"result":{"cache":0,"data":[{"gid":"20190903003","award":"0,6,2,2,3","updatetime":"1567446006"}]},"errorCode":0}
      if (!isset($result['result'])) {
          throw new \Exception("Illegal format result:".$curlResult); 
      }
      if ($result['errorCode'] !== 0) {
          throw new \Exception("Error is Happen:".$curlResult);    
      }
      
    }
}
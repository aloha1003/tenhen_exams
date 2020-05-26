<?php
namespace App\LotteryInstance;
use App\LotteryInstance\BaseLotteryCapturer;
use App\Models\LotteryResult;

use App\Models\Lottery;
class TwoFakeLottery extends BaseLotteryCapturer  {

    
    protected $gameKeyMap = [
        self::KIND_CHONGQINGSHISHICAI => 'cqssc',
        self::KIND_BEIJING => 'bj11x5',
    ]; 
    protected $resultColumnMapping = [
        'issue_at' => 'expect',
        'numbers' => 'opencode',
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
        $urlParams = ['code' => $gameKey];
        $url = 'http://two.fake/newly.do?'.http_build_query($urlParams);
        $ch = curl_init();
        $options = [
                 CURLOPT_URL => $url,
                 CURLOPT_HEADER => false,
                 CURLOPT_POST => false ,
                ];

        curl_setopt_array($ch, $options);
        
        $output = curl_exec($ch); 
        curl_close($ch);
        $this->validCurlResult($output);
        $result = json_decode($curlResult, true);
        $data = $result['data'];
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
      // {"rows":3,"code":"cqssc","data":[{"expect":"20190902003","opencode":"3,8,1,9,5","opentime":"2019-09-02 01:12:46"},{"expect":"20190902002","opencode":"3,1,5,8,6","opentime":"2019-09-02 00:52:37"},{"expect":"20190902001","opencode":"6,1,9,0,3","opentime":"2019-09-02 00:32:03"}]}
      if (!isset($result['result'])) {
          throw new \Exception("Illegal format result:".$curlResult); 
      }
      if ($result['errorCode'] !== 0) {
          throw new \Exception("Error is Happen:".$curlResult);    
      }
      
    }
}
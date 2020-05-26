天瀚國際科技 資深全端工程師問卷問題-解答
===

# Backend
### 1. 請實作下列需求
假設目前有個需求，需要透過多個號源站抓取各彩種指定期號的中獎號碼
可使用 Laravel 或您熟悉的程式語言，請設計出架構，實現剛好滿足需求並具有擴充性程式碼

- 規格如下
    - 需主號源及任一副號源比對相同才成功（主號源只有一個，副號源將會有多個）
    - 不同彩種會有不同主號源需求	
    - `重慶時時彩`，請以號源一為主號源
    - `北京11選5`，請以號源二為主號源
    - 有擴充彩種及號源站需求

> 附註：
> `彩種`為彩票的供應商，例如 `重慶時時彩`、`北京11選5` 等等
> `號源`為第三方 API 廠商，整合各彩種開獎號碼
-  使用方式
```php
<?php
class UpdateWinningNumberJob
{
    protected $lottery;

    public __construct(Lottery $lottery)
    {
        $this->lottery = $lottery;
    }

    public function handle()
    {
        try {
            // $this->lottery->gameId 為 int，指彩種編號，
            // 重慶時時彩 = 1
            // 北京11選5 = 2
            // $this->lottery->issue 為 string , 為此 lottery 期號（e.g. "20190903001"）
            $target = new xxxx($this->lottery); // 請實現此 class
            $this->lottery->update([
                'winning_number' => $target->getWinningNumber();
            ]);
        } catch (FetchFailureException $e) {
            Log::error('Something went wrong.');
        }
    }
}

```

- 號源一 API（為模擬簡化，非正式 API）
`GET http://one.fake/v1?gamekey={gamekey}&issue={issue}`
`gamekey` 為彩種編號
`issue` 為開獎期號

**gamekey 對照表**
`重慶時時彩` => `ssc`
`北京11選5` => `bjsyxw`

```json
// GET http://one.fake/v1?gamekey=ssc&issue=20190903003
{
  "result": {
    "cache": 0,
    "data": [
      {
        "gid": "20190903003",
        "award": "0,6,2,2,3",
        "updatetime": "1567446006"
      }
    ]
  },
  "errorCode": 0
}
```

- 號源二 API（為模擬簡化，非正式 API）
`GET https://two.fake/newly.do?code={code}`
`code` 為彩種編號

**code 對照表**
`重慶時時彩` => `cqssc`
`北京11選5` => `bj11x5`

```json
// GET https://two.fake/newly.do?code=cqssc
{
  "rows": 3,
  "code": "cqssc",
  "data": [
    {
      "expect": "20190902003",
      "opencode": "3,8,1,9,5",
      "opentime": "2019-09-02 01:12:46"
    },
    {
      "expect": "20190902002",
      "opencode": "3,1,5,8,6",
      "opentime": "2019-09-02 00:52:37"
    },
    {
      "expect": "20190902001",
      "opencode": "6,1,9,0,3",
      "opentime": "2019-09-02 00:32:03"
    }
  ]
}
```

### 2. 上面程式或規格可能存在什麼潛在問題？還可以怎樣優化？

1.可以加上快取機制，將查詢的結果快取起來，但是可能要考慮如果信號源資料如果有異動，要怎麼刷新快取
2.可以利用 GuzzleHttp\Promise ,做到非同步請求，加速執行

### 3. 如果要加入第三家號源，會怎麼進行擴充？

1.可以直接建立一筆 號源渠道 lottery_channels 記錄
2.實作該新記錄，對應的 LotteryInstance


### 4. 每個號源有不同的速率限制，會如何實現限流，防止被 ban？
假設號源一限制 5 秒一次，號源二限制 3 秒一次
> 同號源不同彩種是分開計算的

1. 可以用Cache機制，記錄同一源的訪問的時間，再加以比對時間差

### 5. 開獎時間並非準時，您會如何實現重試機制？
號源站並非能第一時間抓到該彩種中獎號碼，因此存在抓不到的可能性



### 6. 可以實現哪些手段來減少程式運行時間？

1. 善用快取
2. 將一部份運算放到隊列去運算
3. 利用非同步的Http請求




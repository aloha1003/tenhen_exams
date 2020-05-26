<?php
namespace App\LotteryInstance;
use App\Models\Lottery;
use App\Contracts\LotteryKind;
use App\Models\LotteryResult;
abstract class BaseLotteryCapturer implements LotteryKind {
    protected $lottery;
    //TODO
    protected $gameKeyMap = [];
    /**
     * [$resultColumnMapping description]
     *   issue_at => curl-result-issue-at-column
     *   numbers => curl-result-numbers-column
     * @var array
     */
    protected $resultColumnMapping = [];     
    public function __construct(Lottery $lottery)
    {
        $this->lottery = $lottery;
    }
    /**
     * [getWinningNumber description]
     * @return [type] [description]
     */
    abstract public function getWinningNumber();

    /**
     * [formatResult description]
     * @return [type] [description]
     */
    protected function formatResult($data)
    {
        $output = [];
        if ($data) {

            foreach ($data as $key => $value) {
                $issueAt = $value[$this->resultColumnMapping['issue_at']] ?? '';
                $numbers = $value[$this->resultColumnMapping['numbers']] ?? '';

                $numbers = explode(',', $numbers);
                sort($numbers);
                $numbers = implode(',', $numbers);
                $output[$issueAt] = app(LotteryResult::class, ['issueAt' => $issueAt, 'numbers' => $numbers])->toArray();
            }
        }
        return $output;
    }



}

<?php
namespace App\Models;
class LotteryResult
{
    public $issueAt;
    public $numbers;
    public function __construct($issueAt, $numbers)
    {
        $this->issueAt = $issueAt;
        $this->numbers = $numbers;
    }
    public function toArray()
    {
        return  [
                    'issueAt' =>  $this->issueAt,
                    'numbers' => $this->numbers
                ];
    }
}
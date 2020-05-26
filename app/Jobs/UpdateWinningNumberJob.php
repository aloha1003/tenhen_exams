<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Lottery;
use App\Service\LotteryService;
class UpdateWinningNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $lottery;
    // 因为laravel 无法直接把ORM 物件丢进来, 所以传入的是阵列
    public function __construct( $lottery)
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
            // $target = new xxxx($this->lottery); // 請實現此 class
            
            $lottery = app(Lottery::class)->find($this->lottery['id']);
            if ($lottery->status === Lottery::STATUS_NO) {  
                $target = app(LotteryService::class, ['lottery' => $lottery]);
                $number = $target->getWinningNumber();
                $updateData = ['status' => Lottery::STATUS_YES, 'winning_number' => $number];
                $lottery->update($updateData);    
            } else {
                throw new \Exception("該期已經提交,不用重覆刷新");
            }
            
        } catch (Exception $e) {
            
            Log::error('Something went wrong.');
        }
    }
}

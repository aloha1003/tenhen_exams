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

    public function __construct(Lottery $lottery)
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
            $target = app(LotteryService::class, ['lottery' => $this->lottery]);
            $this->lottery->update([
                'winning_number' => $target->getWinningNumber();
            ]);
        } catch (FetchFailureException $e) {
            Log::error('Something went wrong.');
        }
    }
}

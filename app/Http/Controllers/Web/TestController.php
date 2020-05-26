<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Lottery;
use App\Jobs\UpdateWinningNumberJob;
class TestController extends Controller {

	public function index()
	{
		$data = request()->only(['game_id', 'issue']);
		$lottery = app(Lottery::class);
		$inputData = [
			'game_id' => $data['game_id'] ?? 1,
			'issue' => $data['issue'] ?? date('Ymd',time()).str_pad(date('H',time()),4,'0',STR_PAD_LEFT),
		];
		$lottery = lottery::firstOrNew($inputData);
		$lottery->save();
		try {
			UpdateWinningNumberJob::dispatch($lottery->toArray());	
		} catch (\Exception $ex) {
			dd($ex);
		}
		

		die("测试完成");
	}
}

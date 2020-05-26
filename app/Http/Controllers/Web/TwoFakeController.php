<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
class TwoFakeController extends Controller {

	public function index()
	{
		$json  = '{"rows":3,"code":"cqssc","data":[{"expect":"20190902003","opencode":"3,8,1,9,5","opentime":"2019-09-02 01:12:46"},{"expect":"20190902002","opencode":"3,1,5,8,6","opentime":"2019-09-02 00:52:37"},{"expect":"20190902001","opencode":"6,1,9,0,3","opentime":"2019-09-02 00:32:03"}]}';
		return response()->json(json_decode($json));
	}
}

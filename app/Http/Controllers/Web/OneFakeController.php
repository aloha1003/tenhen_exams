<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
class OneFakeController extends Controller {

	public function index()
	{
		$json  = '{"result":{"cache":0,"data":[{"gid":"20190903003","award":"0,6,2,2,3","updatetime":"1567446006"}]},"errorCode":0}';
		return response()->json(json_decode($json));
	}
}

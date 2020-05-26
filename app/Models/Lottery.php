<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lottery extends Model
{
	const STATUS_NO = 0;//尚未开奖
	const STATUS_YES = 1; //已开奖
	const STATUS_CANCEL= 2; //已取消
    protected $fillable = ['game_id', 'issue', 'status','winning_number'];
}
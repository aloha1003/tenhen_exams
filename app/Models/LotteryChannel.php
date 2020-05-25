<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LotteryChannel extends Model
{
    const MASTER_GAME_ID_CHONGQINGSHISHICAI = 1;
    const MASTER_GAME_ID_BEIJING = 2;
    protected $table= 'lottery_channels';
    protected $fillable = ['name', 'slug', 'master_game_id'];
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLottery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotteries', function (Blueprint $table) {
            $table->id();
            $table->string('game_id')->comment('彩源名稱');
            $table->string('issue')->comment('发行日期');
            $table->string('winning_number')->comment('该期号码')->nullable();
            $table->tinyInteger('status')->comment('状态:0:尚未有奖号,1:已对奖,2:取消')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lotteries');
    }
}

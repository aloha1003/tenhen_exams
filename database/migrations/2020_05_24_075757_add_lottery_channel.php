<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLotteryChannel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lottery_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('彩源名稱');
            $table->string('slug', 50)->comment('彩種對應程式物件名稱:會對應在App\LotteryInstance\{Slug}')->unique();
            $table->string('master_game_id')->comment('該彩源的主要對應的彩種');
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
        Schema::dropIfExists('lottery_channels');
    }
}

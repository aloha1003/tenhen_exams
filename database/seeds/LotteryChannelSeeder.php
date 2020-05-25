<?php

use Illuminate\Database\Seeder;
use App\Models\LotteryChannel;
class LotteryChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $paddingData = [
            [
                'name' => 'OneFake',
                'slug' => 'OneFakeLottery',
                'master_game_id' => LotteryChannel::MASTER_GAME_ID_CHONGQINGSHISHICAI,
            ],
            [
                'name' => 'TwoFake',
                'slug' => 'TwoFakeLottery',
                'master_game_id' => LotteryChannel::MASTER_GAME_ID_BEIJING,
            ],
        ];
        $allRecords  = app(LotteryChannel::class)->all()->keyBy('slug');
        foreach ($paddingData as $key => $data) {
            if (isset($allRecords[$data['slug']])) {
                continue;
            }
            app(LotteryChannel::class)->create($data);
        }
    }
}

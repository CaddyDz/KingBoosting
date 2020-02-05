<?php

use App\Feature;
use Illuminate\Database\Seeder;

class FeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = [
            'VPN Protection' => [
                'description' => 'To offer the safest lol elo boost on the market, KingBoosting provides VPN protection on every order as a standard. The VPN feature helps to manage the IP address of the booster while boosting on the buyer’s account so the lol boosting process can stay undetected by RIOT.'
            ],
            'Privacy & Appear Offline' => [
                'description' => 'We offer the appear offline feature for players who are looking to have a private boosting experience. This feature can be selected at checkout, and it assures 100% privacy, as the boosted LoL account shows as offline in the friend list while the lol boosting process lasts.',
            ],
            'Finish in time guarantee' => [
                'description' => 'KingBoosting is making sure that your lol elo boost is finished in time. Each submitted order is given a specific deadline and the booster’s goal is to meet this deadline in the fastest possible manner. The length of the boosting process depends on the details of the purchase, and the maximum time limit is indicated when selecting a lol boost.'
            ],
            'Order tracking' => [
                'description' => 'KingBoosting has a built-in order tracking system for each purchased lol elo boost in the member’s area. The system offers different methods of order following, including a chart of the live match history of the LoL account that is being boosted, and a spectate button to watch the booster’s performance.',
            ]
        ];
        foreach ($features as $title => $values) {
            Feature::create([
                'title' => $title,
                'description' => $values['description'],
                'icon' => '/nothing.png'
            ]);
        }
    }
}

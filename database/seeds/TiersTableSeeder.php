<?php

use App\Tier;
use Illuminate\Database\Seeder;

class TiersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tiers = [
            [
                'tier' => 'Iron',
                'box_shadow' => '152,129,108',
                'divisions' => [
                    [
                        'name' => 'Division IV',
                        'image' => '/img/divisions/iron/IV.webp'
                    ],
                    [
                        'name' => 'Division III',
                        'image' => '/img/divisions/iron/III.png'
                    ],
                    [
                        'name' => 'Division II',
                        'image' => '/img/divisions/iron/II.png'
                    ],
                    [
                        'name' => 'Division I',
                        'image' => '/img/divisions/iron/I.png'
                    ],
                ],
                'wins' => [
                    [
                        'wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 2,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 3,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 4,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 5,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 6,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 7,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 8,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 9,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 10,
                        'eta' => '1-2 days'
                    ],
                ]
            ],
            [
                'tier' => 'Bronze',
                'box_shadow' => '89,60,28',
                'division' => [
                    [
                        'name' => 'Division IV',
                        'image' => '/img/divisions/bronze/IV.png'
                    ],
                    [
                        'name' => 'Division III',
                        'image' => '/img/divisions/bronze/III.png'
                    ],
                    [
                        'name' => 'Division II',
                        'image' => '/img/divisions/bronze/II.png'
                    ],
                    [
                        'name' => 'Division I',
                        'image' => '/img/divisions/bronze/I.png'
                    ],
                ],
                'wins' => [
                    [
                        'wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 2,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 3,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 4,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 5,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 6,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 7,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 8,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 9,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 10,
                        'eta' => '1-2 days'
                    ],
                ]
            ],
            [
                'tier' => 'Silver',
                'box_shadow' => '119,134,131',
                'divisions' => [
                    [
                        'name' => 'Division IV',
                        'image' => '/img/divisions/silver/IV.webp'
                    ],
                    [
                        'name' => 'Division III',
                        'image' => '/img/divisions/silver/III.webp'
                    ],
                    [
                        'name' => 'Division II',
                        'image' => '/img/divisions/silver/II.webp'
                    ],
                    [
                        'name' => 'Division I',
                        'image' => '/img/divisions/silver/I.webp'
                    ],
                ],
                'wins' => [
                    [
                        'wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 2,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 3,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 4,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 5,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 6,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 7,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 8,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 9,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 10,
                        'eta' => '1-2 days'
                    ],
                ]
            ],
            [
                'tier' => 'Gold',
                'box_shadow' => '228,189,62',
                'divisions' => [
                    [
                        'name' => 'Division IV',
                        'image' => '/img/divisions/gold/IV.webp'
                    ],
                    [
                        'name' => 'Division III',
                        'image' => '/img/divisions/gold/III.webp'
                    ],
                    [
                        'name' => 'Division II',
                        'image' => '/img/divisions/gold/II.webp'
                    ],
                    [
                        'name' => 'Division I',
                        'image' => '/img/divisions/gold/I.webp'
                    ],
                ],
                'wins' => [
                    [
                        'wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 2,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 3,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 4,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 5,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 6,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 7,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 8,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 9,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 10,
                        'eta' => '1-2 days'
                    ],
                ]
            ],
            [
                'tier' => 'Platinum',
                'box_shadow' => '57,123,135',
                'divisions' => [
                    [
                        'name' => 'Division IV',
                        'image' => '/img/divisions/platinum/IV.webp'
                    ],
                    [
                        'name' => 'Division III',
                        'image' => '/img/divisions/platinum/III.webp'
                    ],
                    [
                        'name' => 'Division II',
                        'image' => '/img/divisions/platinum/II.webp'
                    ],
                    [
                        'name' => 'Division I',
                        'image' => '/img/divisions/platinum/I.webp'
                    ],
                ],
                'wins' => [
                    [
                        'wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 2,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 3,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 4,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 5,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 6,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 7,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 8,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 9,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 10,
                        'eta' => '2-3 days'
                    ],
                ]
            ],
            [
                'tier' => 'Diamond',
                'box_shadow' => '48,113,222',
                'divisions' => [
                    [
                        'name' => 'Division IV',
                        'image' => '/img/divisions/diamond/IV.webp'
                    ],
                    [
                        'name' => 'Division III',
                        'image' => '/img/divisions/diamond/III.webp'
                    ],
                    [
                        'name' => 'Division II',
                        'image' => '/img/divisions/diamond/II.webp'
                    ],
                    [
                        'name' => 'Division I',
                        'image' => '/img/divisions/diamond/I.webp'
                    ],
                ],
                'wins' => [
                    [
                        'wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 2,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 3,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 4,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 5,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 6,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 7,
                        'eta' => '2-3 days'
                    ],
                ]
            ],
            [
                'tier' => 'Master',
                'box_shadow' => '128,29,161',
                'image' => '/img/divisions/master.webp',
                'wins' => [
                    [
                        'wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 2,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 3,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 4,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 5,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 6,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 7,
                        'eta' => '3-5 days'
                    ],
                ]
            ],
            [
                'tier' => 'Grandmaster',
                'box_shadow' => '161,29,29',
                'image' => '/img/divisions/grandmaster.webp',
                'wins' => [
                    [
                        'wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'wins' => 2,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 3,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 4,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 5,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 6,
                        'eta' => '3-5 days'
                    ],
                    [
                        'wins' => 7,
                        'eta' => '3-5 days'
                    ],
                ]
            ],
            [
                'tier' => 'Challenger',
                'box_shadow' => '161,113,29',
                'image' => '/img/divisions/challenger.webp',
                'wins' => [
                    [
                        'wins' => 1,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 2,
                        'eta' => '1-2 days'
                    ],
                    [
                        'wins' => 3,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 4,
                        'eta' => '2-3 days'
                    ],
                    [
                        'wins' => 5,
                        'eta' => '3-5 days'
                    ],
                    [
                        'wins' => 6,
                        'eta' => '3-5 days'
                    ],
                    [
                        'wins' => 7,
                        'eta' => '4-6 days'
                    ],
                ]
            ]
        ];
        foreach ($tiers as $tier) {
            $tierModel = new Tier();
            $tierModel->name = $tier['tier'];
            $tierModel->box_shadow = "inset 60px 0 120px rgba({$tier['box_shadow']},.2), 0 0 9px 0 rgba(0,0,0,.6)";
            if (isset($tier['image'])) { // if tier has an image
                $tierModel->image = $tier['image'];
            }
            $tierModel->save();
            if (isset($tier['divisions'])) {
                $tierModel->divisions()->createMany($tier['divisions']);
            }
            if (isset($tier['wins'])) {
                $tierModel->wins()->createMany($tier['wins']);
            }
        }
    }
}

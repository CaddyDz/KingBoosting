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
                        'Division IV',
                        '/img/divisions/iron/IV.webp'
                    ],
                    [
                        'Division III',
                        '/img/divisions/iron/III.png'
                    ],
                    [
                        'Division II',
                        '/img/divisions/iron/II.png'
                    ],
                    [
                        'Division I',
                        '/img/divisions/iron/I.png'
                    ],
                ],
                'wins' => [
                    [
                        'number_of_wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 2,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 3,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 4,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 5,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 6,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 7,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 8,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 9,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 10,
                        'eta' => '1-2 days'
                    ],
                ]
            ],
            [
                'tier' => 'Bronze',
                'box_shadow' => '89,60,28',
                'division' => [
                    [
                        'Division IV',
                        '/img/divisions/bronze/IV.png'
                    ],
                    [
                        'Division III',
                        '/img/divisions/bronze/III.png'
                    ],
                    [
                        'Division II',
                        '/img/divisions/bronze/II.png'
                    ],
                    [
                        'Division I',
                        '/img/divisions/bronze/I.png'
                    ],
                ],
                'wins' => [
                    [
                        'number_of_wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 2,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 3,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 4,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 5,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 6,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 7,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 8,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 9,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 10,
                        'eta' => '1-2 days'
                    ],
                ]
            ],
            [
                'tier' => 'Silver',
                'box_shadow' => '119,134,131',
                'divisions' => [
                    [
                        'Division IV',
                        '/img/divisions/silver/IV.webp'
                    ],
                    [
                        'Division III',
                        '/img/divisions/silver/III.webp'
                    ],
                    [
                        'Division II',
                        '/img/divisions/silver/II.webp'
                    ],
                    [
                        'Division I',
                        '/img/divisions/silver/I.webp'
                    ],
                ],
                'wins' => [
                    [
                        'number_of_wins' => 1,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 2,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 3,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 4,
                        'eta' => '0-1 day'
                    ],
                    [
                        'number_of_wins' => 5,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 6,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 7,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 8,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 9,
                        'eta' => '1-2 days'
                    ],
                    [
                        'number_of_wins' => 10,
                        'eta' => '1-2 days'
                    ],
                ]
            ],
            [
                'tier' => 'Gold',
                'box_shadow' => '228,189,62',
                [
                    [
                        'Division IV',
                        '/img/divisions/gold/IV.webp'
                    ],
                    [
                        'Division III',
                        '/img/divisions/gold/III.webp'
                    ],
                    [
                        'Division II',
                        '/img/divisions/gold/II.webp'
                    ],
                    [
                        'Division I',
                        '/img/divisions/gold/I.webp'
                    ],
                ],
            ],
            [
                'Platinum',
                '57,123,135',
                [
                    [
                        'Division IV',
                        '/img/divisions/platinum/IV.webp'
                    ],
                    [
                        'Division III',
                        '/img/divisions/platinum/III.webp'
                    ],
                    [
                        'Division II',
                        '/img/divisions/platinum/II.webp'
                    ],
                    [
                        'Division I',
                        '/img/divisions/platinum/I.webp'
                    ],
                ],
            ],
            [
                'Diamond',
                '48,113,222',
                [
                    [
                        'Division IV',
                        '/img/divisions/diamond/IV.webp'
                    ],
                    [
                        'Division III',
                        '/img/divisions/diamond/III.webp'
                    ],
                    [
                        'Division II',
                        '/img/divisions/diamond/II.webp'
                    ],
                    [
                        'Division I',
                        '/img/divisions/diamond/I.webp'
                    ],
                ],
            ],
            [
                'Master',
                '128,29,161',
                '/img/divisions/master.webp'
            ],
            [
                'Grandmaster',
                '161,29,29',
                '/img/divisions/grandmaster.webp'
            ],
            [
                'Challenger',
                '161,113,29',
                '/img/divisions/challenger.webp'
            ]
        ];
        foreach ($tiers as $tier) {
            $hasDivisions = is_array($tier[2]);
            $image = !$hasDivisions ? $tier[2] : null;
            $tierModel = new Tier();
            $tierModel->name = $tier[0];
            $tierModel->box_shadow = "inset 60px 0 120px rgba({$tier[1]},.2), 0 0 9px 0 rgba(0,0,0,.6)";
            $tierModel->image = $image;
            $tierModel->save();
            if ($hasDivisions) {
                // tier[2] is an array, can't seed wins ETA here
                foreach ($tier[2] as $division) {
                    $tierModel->divisions()->create(
                        [
                            'name' => $division[0],
                            'image' => $division[1],
                        ]
                    );
                }
            }
            // Call $tierModel->wins
            // ETA: 1-2 days from iron to gold (5 wins or more)
            // ETA: 2-3 days for platinum (5 wins or more)
            // ETA: 3-5 days for Diamond (5 wins or more)
            // ETA: 4-6 days for Master (5 wins or more)
            // ETA: 5-8 days for Grandmaster and Challenger (5 wins or more)
            if (isset($tier[3])) {
                $tierModel->wins()->create(
                    [
                        'wins' => $tier[3]['number_of_wins'],
                        'eta' => $tier[3]['eta'],
                    ]
                );
            }
        }
    }
}

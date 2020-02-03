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
                'Iron',
                '152,129,108',
                [
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
            ],
            [
                'Bronze',
                '89,60,28',
                [
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
            ],
            [
                'Silver',
                '119,134,131',
                [
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
            ],
            [
                'Gold',
                '228,189,62',
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
                foreach ($tier[2] as $division) {
                    $tierModel->divisions()->create(
                        [
                            'name' => $division[0],
                            'image' => $division[1],
                        ]
                    );
                }
            }
        }
    }
}

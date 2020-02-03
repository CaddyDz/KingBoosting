<?php

use App\Division;
use Illuminate\Database\Seeder;

class DivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisions = [
            [
                'I',
                '152,129,108'
            ],
            [
                'II',
                '89,60,28'
            ],
            [
                'III',
                '119,134,131'
            ],
            [
                'IV',
                '228,189,62'
            ]
        ];
        foreach ($divisions as $division) {
            $image = strtolower($division[0]);
            Division::create([
                'image' => "/img/divisions/{$image}.png",
                'name' => $division[0],
                'box_shadow' => "inset 60px 0 120px rgba({$division[1]},.2), 0 0 9px 0 rgba(0,0,0,.6)"
            ]);
        }
    }
}

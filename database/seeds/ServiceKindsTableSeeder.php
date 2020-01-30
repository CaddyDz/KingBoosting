<?php

use App\ServiceKind;
use Illuminate\Database\Seeder;

class ServiceKindsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kinds = [
            'ELO BOOST',
            'LOL SMURF ACCOUNT SHOP',
            'LOL ACCOUNT',
        ];
        foreach ($kinds as $kind) {
            ServiceKind::create([
                'name' => $kind,
            ]);
        }
    }
}

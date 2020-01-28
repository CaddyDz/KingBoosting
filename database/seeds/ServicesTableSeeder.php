<?php

use App\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Storage::disk('public')->deleteDirectory('services');
        Storage::disk('public')->makeDirectory('services');
        factory(Service::class, 15)->create();
    }
}

<?php

use App\Models\PackageStatus;
use Illuminate\Database\Seeder;

class PackageStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PackageStatus::class, 3)->create();
        //
    }
}

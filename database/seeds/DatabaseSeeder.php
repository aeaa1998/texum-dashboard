<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LockerSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(LotSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(WorkersSeeder::class);
        $this->call(PackageStatusesSeeder::class);
        $this->call(PackageSeeder::class);

    }
}

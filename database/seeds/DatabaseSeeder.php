<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->call(ClientSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(WorkersSeeder::class);
        $this->call(LotSeeder::class);
        $this->call(LockerSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PackageStatusesSeeder::class);
        $this->call(PackageSeeder::class);
        Schema::enableForeignKeyConstraints();
    }
}

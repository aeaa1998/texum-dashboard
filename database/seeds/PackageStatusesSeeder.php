<?php

use App\Models\Package;
use App\Models\PackageStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class PackageStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        PackageStatus::truncate();
        PackageStatus::insert(["name" => "Recibido", "created_at" => $now, "updated_at" => $now]);
        PackageStatus::insert(["name" => "Pendiente de colocar en bodega", "created_at" => $now, "updated_at" => $now]);
        PackageStatus::insert(["name" => "En bodega", "created_at" => $now, "updated_at" => $now]);
        PackageStatus::insert(["name" => "Pendiente de traslado", "created_at" => $now, "updated_at" => $now]);
    }
}

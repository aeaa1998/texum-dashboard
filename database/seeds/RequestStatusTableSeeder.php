<?php

use App\Models\RequestStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RequestStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $now = Carbon::now();
        RequestStatus::insert(["name" => "Aprobado", "slug" => "approved", "created_at" => $now, "updated_at" => $now]);
        RequestStatus::insert(["name" => "Pendiente", "slug" => "pending", "created_at" => $now, "updated_at" => $now]);
        RequestStatus::insert(["name" => "Rechazado", "slug" => "dennied", "created_at" => $now, "updated_at" => $now]);
        Schema::enableForeignKeyConstraints();
    }
}

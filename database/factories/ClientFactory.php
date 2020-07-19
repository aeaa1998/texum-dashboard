<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Client::class, function (Faker $faker) {
    $now = Carbon::now();
    return [
        'name' => $faker->company,
        'created_at' => $now,
        'updated_at' => $now,
    ];
});

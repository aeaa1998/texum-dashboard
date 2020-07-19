<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use App\Models\Lot;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Lot::class, function (Faker $faker) {
    $now = Carbon::now();
    return [
        'number' => $faker->numberBetween(1, 250),
        'client_id' => function () {
            return Client::inRandomOrder()->first()->id;
        },
        'is_delivered' => 0,
        'cut_date' => $faker->date,
        'updated_at' => $now,
        'created_at' => $now,
    ];
});

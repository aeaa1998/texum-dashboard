<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Lot;
use Faker\Generator as Faker;

$factory->define(Lot::class, function (Faker $faker) {
    return [
        'number' => $faker->numberBetween(1,250),
        'client_id' => $faker->numberBetween(1,10),
        'is_delivered' => $faker->numberBetween(1,2),
        'cut_date' => $faker->date,
        'updated_at' => now(),
        'created_at' => now(),
    ];
});

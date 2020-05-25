<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Worker;
use Faker\Generator as Faker;

$factory->define(Worker::class, function (Faker $faker) {
    return [
        'first_name' => $faker->name,
        'last_name' => $faker->lastname,
        'user_id' => $faker->numberBetween(1,20),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

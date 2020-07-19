<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Worker;
use Faker\Generator as Faker;

$factory->define(Worker::class, function (Faker $faker) {
    return [
        'first_name' => $faker->name,
        'last_name' => $faker->lastname,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

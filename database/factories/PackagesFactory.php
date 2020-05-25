<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Package;
use Faker\Generator as Faker;

$factory->define(Package::class, function (Faker $faker) {
    return [
        'bar_code' => $faker->bothify('?#?#?#?#?#'),
        'lot_id' => $faker->numberBetween(1,200),
        'status_id' => $faker->numberBetween(1,3),
        'created_at' => now(),
        'updated_at' => now(),

        //Factory created for Packages
    ];
});

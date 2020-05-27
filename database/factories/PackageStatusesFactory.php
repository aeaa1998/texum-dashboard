<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PackageStatus;
use Faker\Generator as Faker;

$factory->define(PackageStatus::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

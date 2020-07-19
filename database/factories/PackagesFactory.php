<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Locker;
use App\Models\Lot;
use App\Models\Package;
use App\Models\PackageStatus;
use App\Models\Record;
use App\Models\Records;
use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Package::class, function (Faker $faker) {
    $PACKAGE_IN_WAREHOUSE = 3;
    return [
        'bar_code' => $faker->bothify('?#?#?#?#?#'),
        'lot_id' => function () {
            return Lot::inRandomOrder()->first()->id;
        },
        'status_id' => $PACKAGE_IN_WAREHOUSE,
        'created_at' => now(),
        'updated_at' => now(),

        //Factory created for Packages
    ];
});
$factory->afterCreating(Package::class, function ($package, $faker) {
    $record = new Record();
    $now = Carbon::now();
    $locker = Locker::inRandomOrder()->where('capacity', '>', 0)->first();
    $record->package_id = $package->id;
    $record->new_locker = $locker->id;
    $record->user_id = User::whereHas('worker')->inRandomOrder()->first()->id;
    $record->updated_at = $now;
    $record->created_at = $now;
    $record->save();
    $locker->capacity -= 1;
    $locker->save();
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SchoolAttendee;
use Faker\Generator as Faker;

$factory->define(SchoolAttendee::class, function (Faker $faker) {
    return [
        'school_id' => $faker->randomDigit,
        'tournament_id' => $faker->randomDigit,
    ];
});

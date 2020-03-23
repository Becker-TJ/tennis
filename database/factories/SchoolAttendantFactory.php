<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SchoolAttendant;
use Faker\Generator as Faker;

$factory->define(SchoolAttendant::class, function (Faker $faker) {
    return [
        'school_id' => $faker->randomDigit,
        'tournament_id' => $faker->randomDigit
    ];

});

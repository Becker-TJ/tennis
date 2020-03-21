<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tournament;
use Faker\Generator as Faker;

$factory->define(Tournament::class, function (Faker $faker) {
    return [
        'name' => $faker->name . ' Invitational',
        'location' => $faker->locale,
        'host_id' => $faker->randomDigit,
        'total_teams' => 8,
        'gender' => 'M'
    ];
});

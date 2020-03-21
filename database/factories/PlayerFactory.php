<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Player;
use Faker\Generator as Faker;

$factory->define(Player::class, function (Faker $faker) {
    return [
        'first_name' => $faker->name,
        'last_name' => $faker->unique()->safeEmail,
        'school_id' => $faker->randomDigit
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\School;
use Faker\Generator as Faker;

$factory->define(School::class, function (Faker $faker) {
    $conferenceOptions = ['3A','4A','5A','6A'];
    $randomConferenceOption = $conferenceOptions[array_rand($conferenceOptions)];
    return [
        'name' => $faker->name . ' High School',
        'address' => $faker->address,
        'conference' => $randomConferenceOption,
    ];
});

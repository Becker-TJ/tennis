<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tournament;
use Faker\Generator as Faker;

$factory->define(Tournament::class, function (Faker $faker) {
    $levels = ['Varsity', 'Junior Varsity', 'Junior High'];
    $randomLevel = $levels[array_rand($levels)];
    $privacySettings = ['Public', 'Private'];
    $randomPrivacy = $privacySettings[array_rand($privacySettings)];
    $genderSettings = ['Boys', 'Girls', 'Both'];
    $randomGender = $genderSettings[array_rand($genderSettings)];
    return [
        'name' => $faker->name . ' Invitational',
        'location_name' => $faker->city,
        'host_id' => $faker->randomDigit,
        'team_count' => 8,
        'gender' => $randomGender,
        'address' => $faker->address,
        'level' => $randomLevel,
        'privacy_setting' => $randomPrivacy,
        'date' => $faker->date('2020-m-d'),
        'time' => $faker->time('H:i')
    ];
});

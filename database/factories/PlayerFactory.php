<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Player;
use Faker\Generator as Faker;

$factory->define(Player::class, function (Faker $faker) {
    $classOptions = ['Freshman', 'Sophomore', 'Junior', 'Senior'];
    $randomClassOption = $classOptions[array_rand($classOptions)];
    $genders = ['Male', 'Female'];
    $randomGender = $genders[array_rand($genders)];
    static $increment = 1;
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'school_id' => $faker->randomDigit,
        'position' => $increment,
        'class' => $randomClassOption,
        'gender' => $randomGender,
        'one_singles_rank' => 99999,
        'two_singles_rank' => $increment,
        'one_doubles_rank' => $increment,
        'two_doubles_rank' => $increment++
    ];
});

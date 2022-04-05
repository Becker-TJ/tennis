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
        'boys_one_singles_rank' => 99999,
        'boys_two_singles_rank' => $increment,
        'boys_one_doubles_rank' => $increment,
        'boys_two_doubles_rank' => $increment,
        'girls_one_singles_rank' => 99999,
        'girls_two_singles_rank' => $increment,
        'girls_one_doubles_rank' => $increment,
        'girls_two_doubles_rank' => $increment++
    ];
});

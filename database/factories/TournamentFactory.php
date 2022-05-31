<?php

namespace Database\Factories;

use App\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $levels = ['Varsity', 'Junior Varsity', 'Junior High'];
        $randomLevel = $levels[array_rand($levels)];
        $privacySettings = ['Public', 'Private'];
        $randomPrivacy = $privacySettings[array_rand($privacySettings)];
        $genderSettings = ['Boys', 'Girls', 'Both'];
        $randomGender = $genderSettings[array_rand($genderSettings)];

        return [
            'name' => $this->faker->name.' Invitational',
            'location_name' => $this->faker->city,
            'host_id' => $this->faker->randomDigit,
            'team_count' => 8,
            'gender' => $randomGender,
            'address' => $this->faker->address,
            'level' => $randomLevel,
            'privacy_setting' => $randomPrivacy,
            'date' => $this->faker->date('2020-m-d'),
            'time' => $this->faker->time('H:i'),
        ];
    }
}

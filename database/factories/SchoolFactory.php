<?php

namespace Database\Factories;

use App\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $conferenceOptions = ['3A', '4A', '5A', '6A'];
        $randomConferenceOption = $conferenceOptions[array_rand($conferenceOptions)];

        return [
            'name' => $this->faker->name.' High School',
            'address' => $this->faker->address,
            'conference' => $randomConferenceOption,
        ];
    }
}

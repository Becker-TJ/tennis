<?php



namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\SchoolAttendee;

class SchoolAttendeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'school_id' => $this->faker->randomDigit,
            'tournament_id' => $this->faker->randomDigit,
        ];
    }
}

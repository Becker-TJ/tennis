<?php



namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Player;

class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $classOptions = ['Freshman', 'Sophomore', 'Junior', 'Senior'];
        $randomClassOption = $classOptions[array_rand($classOptions)];
        $genders = ['Male', 'Female'];
        $randomGender = $genders[array_rand($genders)];
        static $increment = 1;

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'school_id' => $this->faker->randomDigit,
            'position' => $increment,
            'class' => $randomClassOption,
            'gender' => $randomGender,
            'boys_one_singles_rank' => 99999,
            'boys_two_singles_rank' => $increment,
            'girls_one_singles_rank' => 99999,
            'girls_two_singles_rank' => $increment,
        ];
    }
}

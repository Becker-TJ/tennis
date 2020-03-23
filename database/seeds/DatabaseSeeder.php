<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Player::class, 50)->create();
        factory(App\School::class, 9)->create();
        factory(App\Tournament::class, 9)->create();
        factory(App\User::class, 9)->create();
        factory(App\SchoolAttendant::class, 50)->create();
    }
}

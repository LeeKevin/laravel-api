<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            \App\Domain\Entities\User::create([
                'name' => \App\Domain\ValueObjects\Name::create($faker->firstName, $faker->lastName),
                'email' => $faker->email,
                'password' => $faker->password()
            ])->save();
        }
    }
}
<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AuthenticationTestSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        \App\Domain\Entities\User::create([
            'firstname' => $faker->firstName,
            'lastname'  => $faker->lastName,
            'email'     => 'test@test.com',
            'password'  => 'testPassword'
        ])->save();
    }
}
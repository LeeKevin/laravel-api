<?php

use Illuminate\Database\Seeder;

class DefaultUsersSeeder extends Seeder
{

    public function run()
    {
        \App\Domain\Entities\User::create([
            'firstname' => 'Administrator',
            'lastname'  => '',
            'email'     => 'admin',
            'password'  => 'admin'
        ])->save();
    }
}
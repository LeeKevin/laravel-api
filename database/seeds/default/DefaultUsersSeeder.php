<?php

use Illuminate\Database\Seeder;

class DefaultUsersSeeder extends Seeder
{

    public function run()
    {
        \App\Domain\Entities\User::create([
            'name'     => \App\Domain\ValueObjects\Name::create('Administrator', ''),
            'email'    => 'admin',
            'password' => \Hash::make('admin')
        ])->save();
    }
}
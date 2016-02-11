<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this
            ->truncateTables()
            ->defaultSeeders()
            ->developmentSeeders();
    }

    private function truncateTables()
    {
        \App\Domain\Entities\User::truncate();

        return $this;
    }

    /**
     * Seeders to run in a 'local' environment for development testing
     */
    private function developmentSeeders()
    {
        if (\App::environment() !== 'local') return $this;

        $this->call('UsersSeeder');

        return $this;
    }

    /**
     * Seeders that will run to populate the database with default values
     */
    private function defaultSeeders()
    {
        $this->call('DefaultUsersSeeder');

        return $this;
    }
}

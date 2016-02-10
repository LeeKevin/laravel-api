<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this
            ->defaultSeeders()
            ->developmentSeeders();
    }

    /**
     * Seeders to run in a 'local' environment for development testing
     */
    private function developmentSeeders()
    {
        if (\App::environment() !== 'local') return $this;

        \App\Domain\Entities\User::truncate();
        $this->call('UsersSeeder');

        return $this;
    }

    /**
     * Seeders that will run to populate the database with default values
     */
    private function defaultSeeders()
    {

        return $this;
    }
}

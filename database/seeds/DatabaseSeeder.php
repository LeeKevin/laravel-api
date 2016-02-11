<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this
            ->truncateTables()
            ->defaultSeeders()
            ->developmentSeeders()
            ->testSeeders();
    }

    private function truncateTables()
    {
        \App\Domain\Entities\User::truncate();

        return $this;
    }

    /**
     * Seeders to run in a 'local' environment for development
     */
    private function developmentSeeders()
    {
        if (\App::environment() !== 'local') return $this;

        $this->call('UsersSeeder');

        return $this;
    }

    /**
     * Seeders to run in a 'testing' environment for testing
     */
    private function testSeeders()
    {
        if (\App::environment() !== 'testing') return $this;

        //call all seeders
        $seederFiles = new \RecursiveDirectoryIterator(base_path('database/seeds/test'));
        foreach (new \RecursiveIteratorIterator($seederFiles) as $file) {
            if (!$file->isFile()) continue;
            $fileInfo = pathinfo($file->getFileName());
            $seederName = $fileInfo['filename'];
            $this->call($seederName);
        }

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

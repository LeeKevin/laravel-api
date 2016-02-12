<?php

namespace App\Providers;

use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\Fluent\FluentDriver;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
        }

        //Load Doctrine Repositories into the app:

        $entityFiles = new \RecursiveDirectoryIterator(app_path('/Domain/Entities'));
        foreach (new \RecursiveIteratorIterator($entityFiles) as $file) {
            if (!$file->isFile()) continue;
            $fileInfo = pathinfo($file->getFileName());
            $entityName = $fileInfo['filename'];
            $doctrineRepositoryClass = 'Doctrine' . $entityName . 'Repository';
            if (
                $fileInfo['extension'] == 'php' &&
                file_exists(app_path('/Domain/Repositories') . '/' . $entityName . 'Repository.php') &&
                file_exists(app_path('/Infrastructure/Doctrine/Repositories') . '/' . $doctrineRepositoryClass . '.php')
            ) {
                $this->app->bind("\\App\\Domain\\Repositories\\" . $entityName . 'Repository', function (Application $app) use ($doctrineRepositoryClass, $entityName) {
                    $doctrineRepositoryClassPath = "\\App\\Infrastructure\\Doctrine\\Repositories\\" . $doctrineRepositoryClass;

                    return new $doctrineRepositoryClassPath(
                        $app->make('em'),
                        new ClassMetadata("\\App\\Domain\\Entities\\" . $entityName)
                    );
                });
            }
        }
    }
}

<?php

namespace Crazed\Crudwired\Providers;

use Illuminate\Support\ServiceProvider;

class CrudwiredServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Crazed\Crudwired\Commands\AuthCommand::class,
                \Crazed\Crudwired\Commands\CrudCommand::class,
                \Crazed\Crudwired\Commands\ComponentCommand::class,
                \Crazed\Crudwired\Commands\InstallCommand::class,
                \Crazed\Crudwired\Commands\ListCommand::class,
                \Crazed\Crudwired\Commands\MigrateCommand::class,
                \Crazed\Crudwired\Commands\ModelCommand::class,
                \Crazed\Crudwired\Commands\TailwindCommand::class,
            ]);
        }

        $this->registerConfig();
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }

    protected function registerConfig(){
        $this->mergeConfigFrom(__DIR__.'/../../config/crudwired.php', 'crudwired');
    }
}

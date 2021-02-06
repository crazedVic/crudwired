<?php

namespace Redbastie\Skele\Commands;

use Illuminate\Console\Command;
use Livewire\Commands\ComponentParser;
use Illuminate\Support\Facades\Artisan;

class AuthCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'skele:auth {--force}';

    public function handle()
    {

        $factoryParser = new ComponentParser(config('skele.factory_path'),  config('livewire.view_path'), 'UserFactory');

        $this->createFiles('auth', [
            'components'  =>config('livewire.class_namespace'),
            'views' => config('livewire.view_path'),
            'models' => config('skele.model_path'),
            'factories' => base_path() .DIRECTORY_SEPARATOR . config('skele.factory_path'),
            'DummyModelNamespace' => config('skele.model_path'),
            "DummyComponentNamespace" => config('livewire.class_namespace'),
            'DummyFactoryNamespace' => $factoryParser->classNamespace()

        ], $this->option('force'));

        $command_string = 'skele:migrate';
        $this->info("Calling: " . $command_string);
        Artisan::call($command_string, [], $this->getOutput());

        $this->info('Auth generated!');
    }
    
}

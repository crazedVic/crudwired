<?php

namespace Crazed\Crudwired\Commands;

use Illuminate\Console\Command;
use Livewire\Commands\ComponentParser;
use Illuminate\Support\Facades\Artisan;

class AuthCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'crudwired:auth {--force}';

    public function handle()
    {

        $componentParser = new ComponentParser(config('livewire.class_namespace'), 
            config('livewire.view_path'), 'Home');
        $modelParser = new ComponentParser(config('crudwired.model_path'),  
            config('livewire.view_path'), 'User');
        
        $factory_relative_path = strtolower(str_replace('\\',
            DIRECTORY_SEPARATOR,config('crudwired.factory_path')));

        $component_relative_path = str_replace(['\\', 'App'],
            [DIRECTORY_SEPARATOR, 'app'], config('livewire.class_namespace'));

        $this->info($componentParser->relativeClassPath());
        $this->info($componentParser->relativeViewPath());
        
        $this->createFiles('auth', [
            'components'  => $component_relative_path,
            'views' => config('livewire.view_path'),
            'models' . DIRECTORY_SEPARATOR . 'User.php.stub' => $modelParser->relativeClassPath(),
            'factories' => base_path() .DIRECTORY_SEPARATOR . $factory_relative_path,
            'DummyModelNamespace' => config('crudwired.model_path'),
            "DummyComponentNamespace" => config('livewire.class_namespace')

        ], $this->option('force'));

        $command_string = 'crudwired:migrate';
        $this->info("Calling: " . $command_string);
        Artisan::call($command_string, [], $this->getOutput());

        $this->info('Auth generated!');
    }
    
}

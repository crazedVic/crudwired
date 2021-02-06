<?php

namespace Crazed\Crudwired\Commands;

use Illuminate\Console\Command;
use Livewire\Commands\ComponentParser;

class ModelCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'crudwired:model {class} {--force}';

    public function handle()
    {
        $modelParser = new ComponentParser(config('crudwired.model_path'),  config('livewire.view_path'), $this->argument('class'));
        $factoryParser = new ComponentParser(config('crudwired.factory_path'),  config('livewire.view_path'), $this->argument('class') . 'Factory');

        $this->createFiles('model', [
            'models' . DIRECTORY_SEPARATOR . 'DummyModel.php.stub' => $modelParser->relativeClassPath(),
            'factories' => base_path() .DIRECTORY_SEPARATOR . config('crudwired.factory_path'),
            'DummyModelNamespace' => $modelParser->classNamespace(),
            'DummyModel' => $modelParser->className(),
            'DummyFactoryNamespace' => $factoryParser->classNamespace(),
            'DummyFactory' => $factoryParser->className(),
        ], $this->option('force'));

        $this->warn('<info>' . $this->argument('class') . '</info> model & factory generated!');
        $this->warn("Don't forget to <info>crudwired:migrate</info> after updating the new model.");
    }
}

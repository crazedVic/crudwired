<?php

namespace Crazed\Crudwired\Commands;

use Illuminate\Console\Command;
use Livewire\Commands\ComponentParser;

class TestCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'crudwired:test {class}';

    public function handle()
    {
        $componentParser = new ComponentParser(config('livewire.class_namespace'), config('livewire.view_path'), $this->argument('class'));

        $this->info('app/Components/DummyComponent.php.stub: ' . $componentParser->relativeClassPath());
        $this->info('resources/views/DummyView.blade.php.stub: '. $componentParser->relativeViewPath());
        $this->info('DummyComponentNamespace: ' . $componentParser->classNamespace());
        $this->info('DummyComponent: ' . $componentParser->className());
        $this->info('DummyRouteUri: ' . str_replace('.', '/', $componentParser->viewName()));
        $this->info('DummyViewName: ' . $componentParser->viewName());
        $this->info('DummyViewTitle: ' . preg_replace('/(.)(?=[A-Z])/u', '$1 ', $componentParser->className()));
        $this->info('DummyWisdom: ' . $componentParser->wisdomOfTheTao());

       
    }
}

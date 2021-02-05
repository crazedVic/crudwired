<?php

namespace Redbastie\Skele\Commands;

use Illuminate\Console\Command;
use Livewire\Commands\ComponentParser;

class TestCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'skele:test {class} {--full} {--modal}';

    public function handle()
    {
        $componentParser = new ComponentParser(config('livewire.class_namespace'), config('livewire.view_path'), $this->argument('class'));

        if ($this->option('full')) {
            $stubFolder = 'component-full';
        }
        else if ($this->option('modal')) {
            $stubFolder = 'component-modal';
        }
        else {
            $stubFolder = 'component';
        }
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

<?php

namespace Redbastie\Skele\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Livewire\Commands\ComponentParser;

class ListCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'skele:list {class} {--model=} {--force}';

    public function handle()
    {
        if (!$this->option('model')) {
            $this->warn('A <info>--model</info> must be specified.');
            return;
        }

        $componentParser = new ComponentParser(config('livewire.class_namespace'),  config('livewire.view_path'), $this->argument('class'));
        $modelParser = new ComponentParser(config('skele.model_path'), config('livewire.view_path'), $this->option('model'));

        $this->info('force set? ' . $this->option('force'));

        $this->createFiles('list', [
            'components' . DIRECTORY_SEPARATOR . 'DummyComponent.php.stub' => $componentParser->relativeClassPath(),
            'views' . DIRECTORY_SEPARATOR . 'DummyView.blade.php.stub' => $componentParser->relativeViewPath(),
            'DummyComponentNamespace' => $componentParser->classNamespace(),
            'DummyComponent' => $componentParser->className(),
            'DummyModelNamespace' => $modelParser->classNamespace(),
            'DummyModelVariables' => Str::camel(Str::plural($modelTitle = preg_replace('/(.)(?=[A-Z])/u', '$1 ', $modelParser->className()))),
            'DummyModelVariable' => Str::camel($modelTitle),
            'DummyModel' => $modelParser->className(),
            'DummyRouteUri' => $dummyRouteUri = str_replace('.', '/', strtolower($componentParser->className())),
            'DummyViewName' =>  strtolower($componentParser->viewName()),
            'DummyViewTitle' => preg_replace('/(.)(?=[A-Z])/u', '$1 ', $componentParser->className()),
            'DummyWisdom' => $componentParser->wisdomOfTheTao(),
        ], $this->option('force'));

        $this->warn('<info>' . $this->argument('class') . '</info> list component & view generated! ' .
            '<href=' . url($dummyRouteUri) . '>' . url($dummyRouteUri) . '</>');
    }
}

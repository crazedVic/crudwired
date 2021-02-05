<?php

namespace Redbastie\Skele\Commands;

use Illuminate\Console\Command;
use Livewire\Commands\ComponentParser;

class ComponentCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'skele:component {class} {--full} {--modal} {--force}';

    public function handle()
    {
        
        $subfolder = '';

        if ($this->option('full')) {    
            $stubFolder = 'component-full';
            $subFolder = 'Full.';
        }
        else if ($this->option('modal')) {
            $stubFolder = 'component-modal';
            $subFolder = "Modal.";
        }
        else {
            $stubFolder = 'component';
            $subFolder = "Partial.";
        }

        $componentParser = new ComponentParser(config('livewire.class_namespace'),config('livewire.view_path'), 
                $subFolder . $this->argument('class'));

        
        $this->createFiles($stubFolder, [
            'components' . DIRECTORY_SEPARATOR . 'DummyComponent.php.stub' => $componentParser->relativeClassPath(),
            'views' . DIRECTORY_SEPARATOR . 'DummyView.blade.php.stub' => $componentParser->relativeViewPath(),
            'DummyComponentNamespace' => $componentParser->classNamespace(),
            'DummyComponent' => $componentParser->className(),
            'DummyRouteUri' => $dummyRouteUri = str_replace('.', '/', $componentParser->viewName()),
            'DummyViewName' => $componentParser->viewName(),
            'DummyViewTitle' => preg_replace('/(.)(?=[A-Z])/u', '$1 ', $componentParser->className()),
            'DummyWisdom' => $componentParser->wisdomOfTheTao(),
        ], $this->option('force'));

        $this->warn('<info>' . $this->argument('class') . '</info> component & view generated! ' .
            ($this->option('full') ? '<href=' . url($dummyRouteUri) . '>' . url($dummyRouteUri) . '</>' : ''));
    }
}

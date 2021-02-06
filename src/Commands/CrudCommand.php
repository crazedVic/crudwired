<?php

namespace Crazed\Crudwired\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Livewire\Commands\ComponentParser;

class CrudCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'crudwired:crud {class} {--force}';

    public function handle()
    {
        if ($this->argument('class') == 'User') {
            // todo: fix the crud-user stubs
           // $this->createFiles('crud-user');
            $this->warn('not implemented yet');
           // $this->warn('<info>User</info> CRUD components & views generated! ' .
           //     '<href=' . url('users') . '>' . url('users') . '</>');
        }
        else {

            $modelParser = new ComponentParser(config('crudwired.model_path'), config('livewire.view_path'), $this->argument('class'));
            $modelTitles = Str::plural(preg_replace('/(.)(?=[A-Z])/u', '$1 ', $modelParser->className()));
            $componentClass = Str::replaceLast((string)$modelParser->className(), Str::studly($modelTitles), $this->argument('class'));
            $componentParser = new ComponentParser(config('livewire.class_namespace'), config('livewire.view_path'), $componentClass);

            $this->createFiles('crud', [
                'components' . DIRECTORY_SEPARATOR . 'DummyModels' => str_replace('.php', '', $componentParser->relativeClassPath()),
                'views' . DIRECTORY_SEPARATOR . 'DummyViews' => str_replace('.blade.php', '', $componentParser->relativeViewPath()),
                'DummyComponentNamespace' => $componentParser->classNamespace() . '\\' . $componentParser->className(),
                'DummyModelNamespace' => $modelParser->classNamespace(),
                'DummyModelVariables' => Str::camel($modelTitles),
                'DummyModelVariable' => Str::camel(Str::singular($modelTitles)),
                'DummyModelTitles' => $modelTitles,
                'DummyModelTitle' => Str::singular($modelTitles),
                'DummyModel' => $modelParser->className(),
                'DummyRouteUri' => $dummyRouteUri = str_replace('.', '/', strtolower($componentParser->className())),
                'DummyViewName' =>  strtolower($componentParser->viewName()),
            ], $this->option('force'));

            $this->warn('<info>' . $this->argument('class') . '</info> CRUD components & views generated! ' .
                '<href=' . url($dummyRouteUri) . '>' . url($dummyRouteUri) . '</>');

            if (!$this->fileExists($modelParser->relativeClassPath() || $this->option('force') )) {
                $command_string = 'crudwired:model ' . $this->argument('class') . ($this->option('force') ? ' --force': null);
                $this->info("Calling: " . $command_string);
                Artisan::call($command_string, [], $this->getOutput());
            }
        }
    }
}

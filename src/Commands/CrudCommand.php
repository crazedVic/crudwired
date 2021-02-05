<?php

namespace Redbastie\Skele\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Livewire\Commands\ComponentParser;

class CrudCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'skele:crud {class} {--force}';

    public function handle()
    {
        if ($this->argument('class') == 'User') {
            $this->createFiles('crud-user');

            $this->warn('<info>User</info> CRUD components & views generated! ' .
                '<href=' . url('users') . '>' . url('users') . '</>');
        }
        else {

            // $this->info('Components path: '. config('livewire.class_namespace'));
            // $this->info('Model path: ' . config('skele.model_path'));
            // $this->info('Resource path: '. config('livewire.view_path'));

            $modelParser = new ComponentParser(config('skele.model_path'), config('livewire.view_path'), $this->argument('class'));
            $modelTitles = Str::plural(preg_replace('/(.)(?=[A-Z])/u', '$1 ', $modelParser->className()));
            $componentClass = Str::replaceLast((string)$modelParser->className(), Str::studly($modelTitles), $this->argument('class'));
            $componentParser = new ComponentParser(config('livewire.class_namespace'), config('livewire.view_path'), $componentClass);


            // $this->info('components/DummyModels: ' .str_replace('.php', '', $componentParser->relativeClassPath()));
            // $this->info('views/DummyViews: '. str_replace('.blade.php', '', $componentParser->relativeViewPath()));
            // $this->info('DummyComponentNamespace: ' . $componentParser->classNamespace() . '\\' . $componentParser->className());
            // $this->info('DummyModelNamespace: ' .$modelParser->classNamespace());
            // $this->info('DummyModelVariables: ' . Str::camel($modelTitles));
            // $this->info('DummyModelVariable: '.Str::camel(Str::singular($modelTitles)));
            // $this->info('DummyModelTitles: ' . $modelTitles);
            // $this->info('DummyModelTitle: '. Str::singular($modelTitles));
            // $this->info('DummyModel: ' . $modelParser->className());
            // $this->info('DummyRouteUri: ' . str_replace('.', '/', $componentParser->viewName()));
            // $this->info('DummyViewName: ' . $componentParser->viewName());
           

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
                'DummyRouteUri' => $dummyRouteUri = str_replace('.', '/', $componentParser->viewName()),
                'DummyViewName' => $componentParser->viewName(),
            ], $this->option('force'));

            $this->warn('<info>' . $this->argument('class') . '</info> CRUD components & views generated! ' .
                '<href=' . url($dummyRouteUri) . '>' . url($dummyRouteUri) . '</>');

            if (!$this->fileExists($modelParser->relativeClassPath() || $this->option('force') )) {
                Artisan::call('skele:model ' . $this->argument('class'), [], $this->getOutput());
            }
        }
    }
}

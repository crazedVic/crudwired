<?php

namespace Crazed\Crudwired\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Livewire\Commands\ComponentParser;

class InstallCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'crudwired:install  {--force}';

    public function handle()
    {
        $componentParser = new ComponentParser(config('livewire.class_namespace'), 
            config('livewire.view_path'), 'Index');

        $this->createFiles('install' . DIRECTORY_SEPARATOR . 'add', [
            'components' . DIRECTORY_SEPARATOR . 'Index.php.stub' => $componentParser->relativeClassPath(),
            'views' . DIRECTORY_SEPARATOR . 'index.blade.php.stub' => $componentParser->relativeViewPath(),
            'DummyAppName' => config('app.name'),
        ], $this->option('force'));

        $this->modifyFiles('install' . DIRECTORY_SEPARATOR . 'modify');

        $this->info('Installation complete! <href=' . config('app.url') . '>' . config('app.url') . '</>');
    }
}

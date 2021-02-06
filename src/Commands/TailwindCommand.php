<?php

namespace Crazed\Crudwired\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Livewire\Commands\ComponentParser;

class TailwindCommand extends Command
{
    use ManagesFiles;

    protected $signature = 'crudwired:tailwind  {--force}';

    public function handle()
    {
        $componentParser = new ComponentParser(config('livewire.class_namespace'), 
            config('livewire.view_path'), 'Index');

        $this->createFiles('tailwind' . DIRECTORY_SEPARATOR . 'add', [], $this->option('force'));

        $this->modifyFiles('tailwind' . DIRECTORY_SEPARATOR . 'modify');

        exec('npm install');
        exec('npm install tailwindcss@latest postcss@latest autoprefixer@latest @tailwindcss/forms -D');
        exec('npm run dev');

        $this->info('Tailwind install complete!');
    }
}

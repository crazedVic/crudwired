<?php

namespace Redbastie\Skele\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

trait ManagesFiles
{
    private $filesystem;

    private function filesystem()
    {
        if (!$this->filesystem) {
            $this->filesystem = new Filesystem;
        }

        return $this->filesystem;
    }

    protected function modifyFiles($stubFolder){
        $working_folder = str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/../../resources/stubs/' . $stubFolder);

        foreach ($this->filesystem()->allFiles($working_folder) as $file) {
            $this->info('processing file: ' . $file);
            $filePath = Str::replaceLast('.stub', '', $file->getRelativePathname());
            // fix for multi-OS support
            $filePath = str_replace('/', DIRECTORY_SEPARATOR, $filePath);
            $this->warn("Will attempt to modify file:  " . $filePath);
            if ($this->filesystem()->exists($filePath)) {
                $existing_file = $this->filesystem()->get($filePath);
                if( !str_contains($existing_file, trim($file->getContents()))){
                    $this->filesystem()->append($filePath,$file->getContents());
                    $this->info('file has been updated successfully');
                }
                else{
                    $this->info('file already updated');
                }
            }
            else{
                $this->warn("Existing file not found");
            }
        }
    }

    protected function createFiles($stubFolder, $replaces = [], $force = false)
    {
        $working_folder = str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/../../resources/stubs/' . $stubFolder);

        foreach ($this->filesystem()->allFiles($working_folder) as $file) {
            $this->info('processing file: ' . $file);
            // remove stub
            $filePath = Str::replaceLast('.stub', '', $this->replace($replaces, $file->getRelativePathname()));
            // fix for multi-OS support
            $filePath = str_replace('/', DIRECTORY_SEPARATOR, $filePath);
            // show output
            $this->warn("copying to " . $filePath);

            if ($fileDir = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, $filePath), 0, -1))) {
                $this->filesystem()->ensureDirectoryExists($fileDir);
            }

           // $this->filesystem()->put($filePath, $this->replace($replaces, $file->getContents()));
           // $this->warn('Created file: <info>' . $filePath . '</info>');

            // prevent override by default
            if ($force || !$this->filesystem()->exists($filePath)) {
                $this->filesystem()->put($filePath, $this->replace($replaces, $file->getContents()));
                $this->info('Created file: <info>' . $filePath . '</info>');
            }
            else{
                 $this->warn('Unable to create file: <info>' . $filePath . '</info>. File already exists.  Use --force to override.');
            }
        }
    }

    protected function deleteFiles($filePaths)
    {
        foreach ($filePaths as $filePath) {
            if ($this->fileExists($filePath)) {
                $this->filesystem()->delete($filePath);
                $this->warn('Deleted file: <info>' . $filePath . '</info>');
            }
        }
    }

    protected function fileExists($filePath)
    {
        return $this->filesystem()->exists($filePath);
    }

    private function replace($replaces, $contents)
    {
        foreach ($replaces as $search => $replace) {
            $contents = str_replace($search, $replace, $contents);
        }

        return $contents;
    }

    private function mapPath($originalPath)
    {
        $arr = explode(DIRECTORY_SEPARATOR, $originalPath, 2);
        $category = $arr[0];
        $filename = $arr[1];

        switch ($category){
            case 'views':
                return config('livewire.view_path') . DIRECTORY_SEPARATOR . $filename;
                break;
            case "models":
                return config('skele.model_path') . DIRECTORY_SEPARATOR . $filename;
                break;
            case "components":
                return config('livewire.class_namespace') . DIRECTORY_SEPARATOR . $filename;
                break;
            case "factories":
                return config('skele.factory_path') . DIRECTORY_SEPARATOR . $filename;
                break;
            default:
                return $originalPath;

        }
    }
}

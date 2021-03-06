<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    
    $filesystem = new Filesystem;
    //$dir = base_path() . DIRECTORY_SEPARATOR .  config('livewire.class_namespace');

    $component_relative_path = str_replace(['\\', 'App'],
            [DIRECTORY_SEPARATOR, 'app'], config('livewire.class_namespace'));

    $dir = base_path() . DIRECTORY_SEPARATOR . $component_relative_path;

    if ($filesystem->exists($dir)) {
        foreach ($filesystem->allFiles($dir) as $file) {

            $namespace = config('livewire.class_namespace') . "\\" . 
                str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());

            $class = app($namespace);         

            if (property_exists($class, 'routeUri') && $class->routeUri) {
                $route = Route::get($class->routeUri, $namespace);
                //error_log($class->routeUri);

                if (property_exists($class, 'routeName') && $class->routeName) {
                    $route->name($class->routeName);
                    //error_log($class->routeName);
              }

                if (property_exists($class, 'routeMiddleware') && $class->routeMiddleware) {
                    $route->middleware($class->routeMiddleware);
                }

                if (property_exists($class, 'routeDomain') && $class->routeDomain) {
                    $route->domain($class->routeDomain);
                }

                if (property_exists($class, 'routeWhere') && $class->routeWhere) {
                    $route->where($class->routeWhere);
                }
            }
        }
    }
});

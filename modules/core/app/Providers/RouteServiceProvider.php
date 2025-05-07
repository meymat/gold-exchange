<?php

namespace Modules\core\app\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $modules = config('app.modules');
        foreach ($modules as $module) {
            $baseModulePath = base_path('modules/' . $module . '/routes');

            if (is_dir($baseModulePath)) {
                $directory = new \RecursiveDirectoryIterator($baseModulePath);
                $iterator = new \RecursiveIteratorIterator($directory);
                $regex = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

                foreach ($regex as $filePath => $fileInfo) {
                    $relativePath = str_replace(base_path(), '', $filePath);
                    $pathParts = explode(DIRECTORY_SEPARATOR, $relativePath);
                   // $routeNamePrefix = pathinfo(array_pop($pathParts), PATHINFO_FILENAME).'.';

                    $isApiRoute = in_array('api.php', $pathParts);

                    if ($isApiRoute) {
                        $versionDirIndex = array_search('api.php', $pathParts) - 1;
                        $apiVersion = $pathParts[$versionDirIndex] ?? null;

                        if ($apiVersion) {
                            $namespace = 'Modules\\' . $module . '\\app\Http\Controllers\Api\\' . $apiVersion;
                            Route::middleware('api')
                                ->prefix('api/' . $apiVersion)
                                ->namespace($namespace)
                                ->group($filePath);
                        }
                    } else {
                        $namespace = 'Modules\\' . $module . '\\app\Http\Controllers';
                        Route::middleware('web')
                           // ->as($routeNamePrefix)
                            ->namespace($namespace)
                            ->group($filePath);
                    }
                }
            }
        }
    }
}

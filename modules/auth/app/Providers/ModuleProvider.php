<?php
namespace Modules\auth\app\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleProvider extends ServiceProvider
{
    protected string $module_name = 'auth';

    public function register() : void
    {
        $this->loadMigrationsFrom(base_path('modules/'.$this->module_name.'/database/migrations'));
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/sanctum.php',
            'sanctum'
        );
    }

    public function boot() : void
    {

    }
}

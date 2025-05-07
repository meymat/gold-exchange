<?php
namespace Modules\trade\app\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleProvider extends ServiceProvider
{
    protected string $module_name = 'trade';

    public function register() : void
    {
        $this->loadMigrationsFrom(base_path('modules/'.$this->module_name.'/database/migrations'));
    }

    public function boot() : void
    {

    }
}

<?php

namespace Modules\core\app\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Modules\core\app\Exceptions\ModuleHandler;


class ModuleProvider extends ServiceProvider
{
    protected string $module_name = 'core';

    public function register(): void
    {
        require_once base_path('modules/' . $this->module_name . '/app/helpers.php');
        addModulesProviders();
    }

    public function boot(): void
    {

    }
}

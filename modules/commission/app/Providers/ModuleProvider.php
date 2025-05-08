<?php

namespace Modules\commission\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\commission\app\Interfaces\CommissionRuleRepositoryInterface;
use Modules\commission\app\Repositories\CommissionRuleRepository;

class ModuleProvider extends ServiceProvider
{
    protected string $module_name = 'commission';

    public function register(): void
    {
        $this->loadMigrationsFrom(base_path('modules/' . $this->module_name . '/database/migrations'));

        $this->app->bind(
            CommissionRuleRepositoryInterface::class,
            CommissionRuleRepository::class
        );
    }

    public function boot(): void
    {

    }
}

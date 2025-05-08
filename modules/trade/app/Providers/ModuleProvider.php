<?php
namespace Modules\trade\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\trade\app\Interfaces\TradeRepositoryInterface;
use Modules\trade\app\Repositories\TradeRepository;

class ModuleProvider extends ServiceProvider
{
    protected string $module_name = 'trade';

    public function register() : void
    {
        $this->loadMigrationsFrom(base_path('modules/'.$this->module_name.'/database/migrations'));

        $this->app->bind(
            TradeRepositoryInterface::class,
            TradeRepository::class
        );
    }

    public function boot() : void
    {

    }
}

<?php
namespace Modules\order\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\order\app\Interfaces\OrderRepositoryInterface;
use Modules\order\app\Repositories\OrderRepository;

class ModuleProvider extends ServiceProvider
{
    protected string $module_name = 'order';

    public function register() : void
    {
        $this->loadMigrationsFrom(base_path('modules/'.$this->module_name.'/database/migrations'));
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/order.php',
            'order'
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );
    }

    public function boot() : void
    {

    }
}

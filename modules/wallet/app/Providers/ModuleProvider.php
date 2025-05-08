<?php
namespace Modules\wallet\app\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\wallet\app\Interfaces\WalletRepositoryInterface;
use Modules\wallet\app\Repositories\WalletRepository;

class ModuleProvider extends ServiceProvider
{
    protected string $module_name = 'wallet';

    public function register() : void
    {
        $this->loadMigrationsFrom(base_path('modules/'.$this->module_name.'/database/migrations'));

        $this->app->bind(
            WalletRepositoryInterface::class,
            WalletRepository::class
        );
    }

    public function boot() : void
    {

    }
}

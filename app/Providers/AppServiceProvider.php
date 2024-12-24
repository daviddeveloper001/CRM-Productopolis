<?php

namespace App\Providers;
use App\Models\Campaign;
use App\Services\CityServices;
use App\Services\EventService;
use App\Actions\SchedulingAction;

use App\Services\CountryServices;
use App\Services\CustomerServices;
use App\Actions\DemonstrationAction;
use App\Actions\MedicalAction;
use App\Models\Block;
use App\Observers\BlockObserver;
use App\Services\DepartmentServices;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SchedulingAction::class, function ($app) {
            return new SchedulingAction(
                $app->make(CityServices::class),
                $app->make(DepartmentServices::class),
                $app->make(CountryServices::class),
                $app->make(CustomerServices::class),
                $app->make(EventService::class),
            );
        });

        $this->app->singleton(DemonstrationAction::class, function ($app) {
            return new DemonstrationAction(
                $app->make(CityServices::class),
                $app->make(DepartmentServices::class),
                $app->make(CountryServices::class),
                $app->make(CustomerServices::class),
                $app->make(EventService::class),
            );
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Block::observe(BlockObserver::class);
    }
}

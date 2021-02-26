<?php

namespace App\Providers;

use App\Adapters\BlockchainInfoRateAdapter;
use App\Services\ConvertService;
use App\Services\RateService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RateService::class, static function () {
            return new RateService(app(BlockchainInfoRateAdapter::class), env('FEE_PERCENT', 2));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
    }
}

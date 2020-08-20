<?php

namespace App\Providers;

use Bezhanov\Faker\ProviderCollectionHelper;
use Faker\Generator;
use Illuminate\Support\DateFactory;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Date\Date;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DateFactory::useClass(Date::class);

        $this->app->extend(Generator::class, function (Generator $generator) {
            ProviderCollectionHelper::addAllProvidersTo($generator);
            return $generator;
        });
    }
}

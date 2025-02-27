<?php

namespace Conversion\Providers;

use Illuminate\Support\ServiceProvider;
use Conversion\Converter;
use Conversion\Parser;
use Conversion\Registry;
use Conversion\RegistryBuilder;

class ConversionServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->app->singleton('converter', function ($app) {
            return new Converter();
        });

        $this->app->singleton('parser', function ($app) {
            $registry = $app->make('registry');

            return new Parser($registry);
        });

        $this->app->singleton('registry', function ($app) {
            return new Registry();
        });
    }

    public function boot()
    {
    }
}


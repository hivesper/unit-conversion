<?php

namespace Conversion\Providers;

use Conversion\Parser;
use Conversion\Registry;
use Illuminate\Support\ServiceProvider;
use Conversion\Converter;

class ConversionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Converter::class, function ($app) {
            return new Converter();
        });

        $this->app->singleton(Parser::class, function ($app) {
            $registry = $app->make('registry');

            return new Parser($registry);
        });

        $this->app->singleton(Registry::class, function ($app) {
            return new Registry();
        });
    }
}


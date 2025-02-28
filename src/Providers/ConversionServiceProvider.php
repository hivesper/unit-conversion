<?php

namespace Conversion\Providers;

use Illuminate\Support\ServiceProvider;
use Conversion\Converter;

class ConversionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Converter::class, function ($app) {
            return new Converter();
        });
    }
}


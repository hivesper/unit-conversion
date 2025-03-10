<?php declare(strict_types=1);

namespace Vesper\UnitConversion\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Vesper\UnitConversion\Converter;
use Vesper\UnitConversion\Parser;
use Vesper\UnitConversion\Registry;
use Vesper\UnitConversion\RegistryBuilder;

class ConversionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Converter::class, function () {
            return new Converter();
        });

        $this->app->singleton(Parser::class, function (Application $app) {
            $registry = $app->make(Registry::class);

            return new Parser($registry);
        });

        $this->app->singleton(Registry::class, function () {
            $registry = new Registry();

            RegistryBuilder::build($registry);

            return $registry;
        });
    }
}
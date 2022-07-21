<?php

namespace Toyi\Identifier;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class IdentifierServiceProvider extends ServiceProvider
{
    public function register()
    {
        Blueprint::macro('identifier', function (string $key = 'identifier') {
            return $this->string($key)->unique();
        });

        $this->publishes([
            __DIR__.'/../config/identifier.php' => config_path('identifier.php'),
        ], 'laravel-api');

        $this->mergeConfigFrom(__DIR__.'/../config/identifier.php', 'identifier');
    }
}

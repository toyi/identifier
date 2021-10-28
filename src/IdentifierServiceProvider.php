<?php namespace Toyi\Identifier;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class IdentifierServiceProvider extends ServiceProvider
{
    public function register()
    {
        Blueprint::macro('identifier', function(string $key = 'identifier'){
            return $this->string($key)->unique();
        });
    }
}

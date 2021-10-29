<?php namespace Toyi\LaravelIdentifier\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Toyi\LaravelIdentifier\IdentifierServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function defineDatabaseMigrations()
    {
        Schema::create('identifier_models', function (Blueprint $table) {
            $table->id();
            $table->identifier();
            $table->identifier('other_key');
            $table->string('name');
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            IdentifierServiceProvider::class
        ];
    }
}

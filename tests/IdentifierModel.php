<?php namespace Toyi\LaravelIdentifier\Tests;

use Illuminate\Database\Eloquent\Model;
use Toyi\LaravelIdentifier\HasIdentifier;

class IdentifierModel extends Model
{
    use HasIdentifier;

    public $timestamps = false;

    public static string $key = 'identifier';

    public static function getIdentifierKey(): string
    {
        return static::$key;
    }
}

<?php namespace Toyi\Identifier\Tests;

use Illuminate\Database\Eloquent\Model;
use Toyi\Identifier\HasIdentifier;

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

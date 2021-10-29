<?php namespace Toyi\Identifier;

/**
 * Trait HasIdentifier
 *
 * @package Toyi\Identifier
 */
trait HasIdentifier
{
    protected static array $fetchedIdentifiers = [];

    public static function getIdentifierKey(): string
    {
        return 'identifier';
    }

    /**
     * @param string $identifier
     * @param array $attributes
     * @return HasIdentifier|null
     */
    public static function getModelByIdentifier(string $identifier, array $attributes = ['*']): ?static
    {
        return static::query()->where(static::getIdentifierKey(), '=', $identifier)->first($attributes);
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public static function getIdByIdentifier(string $identifier): mixed
    {
        if (static::identifierHasBeenFetched($identifier)) {
            return static::$fetchedIdentifiers[static::class][$identifier];
        }

        $id = optional(static::getModelByIdentifier($identifier, ['id']))->id;

        if ($id != null) {
            static::$fetchedIdentifiers[static::class][$identifier] = $id;
        }

        return $id;
    }

    public static function checkIdentifier(mixed $id, string $identifier): bool
    {
        return static::getIdByIdentifier($identifier) == $id;
    }

    public static function getIdentifierById(mixed $id): mixed
    {
        return optional(static::query()->find($id))->identifier;
    }

    public static function identifierHasBeenFetched(string $identifier): bool
    {
        return isset(static::$fetchedIdentifiers[static::class][$identifier]);
    }

    public static function resetFetchedIdentifiers(): void
    {
        static::$fetchedIdentifiers[static::class] = null;
        unset(static::$fetchedIdentifiers[static::class]);
    }

    public function __toString()
    {
        return $this->{static::getIdentifierKey()};
    }
}

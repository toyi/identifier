<?php

namespace Toyi\Identifier;

/**
 * Trait HasIdentifier
 */
trait HasIdentifier
{
    protected static array $fetchedIdentifiers = [];

    protected static function bootHasIdentifier()
    {
        if (config('identifier.cache.enabled') === false) {
            static::resetFetchedIdentifiers();
        }
    }

    public static function getIdentifierKey(): string
    {
        return 'identifier';
    }

    /**
     * @param string $identifier
     * @param array $attributes
     * @return mixed
     */
    public static function getModelByIdentifier(string $identifier, array $attributes = ['*']): ?static
    {
        if (!in_array('*', $attributes) && !in_array('id', $attributes)) {
            $attributes[] = 'id';
        }

        $cache_key = static::identifierCacheKey($identifier, $attributes);

        if (static::identifierHasBeenFetched($cache_key) && config('identifier.cache.enabled')) {
            return static::$fetchedIdentifiers[static::class][$cache_key];
        }

        $model = static::query()->where(static::getIdentifierKey(), '=', $identifier)->first($attributes);

        if ($model != null && config('identifier.cache.enabled')) {
            static::$fetchedIdentifiers[static::class][$cache_key] = $model;
        }

        return $model;
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public static function getIdByIdentifier(string $identifier): mixed
    {
        $attributes = ['id'];
        $model = static::getModelByIdentifier($identifier, $attributes);

        return optional($model)->id;
    }

    public static function checkIdentifier(mixed $id, string $identifier): bool
    {
        return static::getIdByIdentifier($identifier) == $id;
    }

    public static function getIdentifierById(mixed $id): mixed
    {
        return optional(static::query()->find($id))->identifier;
    }

    public static function identifierHasBeenFetched(string $key): bool
    {
        return isset(static::$fetchedIdentifiers[static::class][$key]);
    }

    public static function resetFetchedIdentifiers(): void
    {
        static::$fetchedIdentifiers[static::class] = null;
        unset(static::$fetchedIdentifiers[static::class]);
    }

    protected function finishSave(array $options)
    {
        static::resetFetchedIdentifiers();
        parent::finishSave($options);
    }

    public function __toString()
    {
        return $this->{static::getIdentifierKey()};
    }

    private static function identifierCacheKey(string $identifier, array $attributes): string
    {
        return $identifier . '.' . implode('.', $attributes);
    }
}

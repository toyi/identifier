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
     * @return mixed
     */
    public static function getModelByIdentifier(string $identifier, array $attributes = ['*']): ?static
    {
        if (!in_array('*', $attributes) && !in_array('id', $attributes)) {
            $attributes[] = 'id';
        }

        if (static::identifierHasBeenFetched($identifier)) {
            return static::$fetchedIdentifiers[static::class][$identifier];
        }

        $model = static::query()->where(static::getIdentifierKey(), '=', $identifier)->first($attributes);

        if ($model != null) {
            static::$fetchedIdentifiers[static::class][$identifier] = $model;
        }

        return $model;
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public static function getIdByIdentifier(string $identifier): mixed
    {
        static::getModelByIdentifier($identifier, ['id']);

        return optional(static::$fetchedIdentifiers[static::class][$identifier] ?? null)->id;
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

    protected function finishSave(array $options)
    {
        static::resetFetchedIdentifiers();
        parent::finishSave($options);
    }

    public function __toString()
    {
        return $this->{static::getIdentifierKey()};
    }
}

<?php

namespace Toyi\Identifier;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IdentifiableCollection extends ResourceCollection
{
    /**
     * @throws Exception
     */
    public function toArray($request): array
    {
        $model = $this->collection[0] ?? null;

        if (! $model) {
            return [
                'data' => [
                    'list' => [],
                ],
            ];
        }

        if(!method_exists($model, 'getIdentifierKey')){
            throw new Exception("{$model::class} does not use HasIdentifier trait from toyi/identifier.");
        }

        $identifier_name = $model->getIdentifierKey();
        $pk_name = $model->getKeyName();

        return [
            'data' => [
                'list' => $this->collection->toResourceCollection(),
                $identifier_name => $this->collection->mapWithKeys(function (Model $res) use ($identifier_name) {
                    return [$res[$identifier_name] => $res->toResource()];
                }),
                $pk_name => $this->collection->mapWithKeys(function (Model $res) use ($pk_name) {
                    return [$res[$pk_name] => $res->toResource()];
                }),
            ],
        ];
    }
}

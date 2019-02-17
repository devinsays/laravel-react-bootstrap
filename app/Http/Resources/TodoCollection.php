<?php

namespace App\Http\Resources;

use App\Todo;
use App\Http\Resources\ApiResourceCollection;
use App\Http\Resources\TodoResource;

class TodoCollection extends ApiResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Transforms the collection to match format in TodoResource.
        $this->collection->transform(function (Todo $todo) {
            return (new TodoResource($todo));
        });

        return parent::toArray($request);
    }
}

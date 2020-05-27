<?php

namespace App\Http\Resources;

use App\Custom\Hasher;

class TodoResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'created_at' => (string)$this->created_at->toDateTimeString(),
            'updated_at' => (string)$this->updated_at->toDateTimeString(),
            'id' => $this->id,
            'user' => Hasher::encode($this->user_id),
            'value' => $this->value,
            'status' => $this->status,
        ];
    }
}

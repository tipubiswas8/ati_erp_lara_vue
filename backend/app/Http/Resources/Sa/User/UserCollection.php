<?php

namespace App\Http\Resources\Sa\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'status' => true,
            'message' => 'All user fetch',
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}

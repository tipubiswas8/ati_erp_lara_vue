<?php

namespace App\Http\Resources\Sa\Role;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
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
            'message' => 'All role fetch',
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}

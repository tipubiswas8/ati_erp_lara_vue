<?php

namespace App\Http\Resources\Sa\Permission;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
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
            'message' => 'All permission fetch',
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}

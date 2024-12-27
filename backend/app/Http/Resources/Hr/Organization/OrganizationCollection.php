<?php

namespace App\Http\Resources\Hr\Organization;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrganizationCollection extends ResourceCollection
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
            'data' => $this->collection->map(function ($request) {
                return new OrganizationResource($request);
            }),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}

<?php

namespace App\Http\Resources\Hr\Employee;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployeeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return [
        //     'data' => $this->collection->map(function ($request) {
        //         return new EmployeeCollection($request);
        //     }),
        //     'meta' => [
        //         'total' => $this->collection->count(),
        //     ],
        // ];
        return [
            'data' => EmployeeResource::collection($this->collection), // Wrap each employee in EmployeeResource
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ],
        ];
    }
}

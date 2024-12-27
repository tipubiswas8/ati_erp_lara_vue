<?php

namespace App\Http\Resources\Sa\Role;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'role_name' => $this->name,
            'status' => $this->status,
            'org_id' => $this->organization->org_id,
            'org_name' => $this->organization->org_name,
        ];
    }

}

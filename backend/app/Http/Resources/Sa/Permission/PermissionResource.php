<?php

namespace App\Http\Resources\Sa\Permission;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'permission_name' => $this->name,
            'org_name' => $this->organization->org_name,
            'org_id' => $this->organization->org_id,
            'status' => $this->status,
        ];
    }

}

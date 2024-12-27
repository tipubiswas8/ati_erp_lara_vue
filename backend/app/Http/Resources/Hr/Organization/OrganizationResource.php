<?php

namespace App\Http\Resources\Hr\Organization;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
        // return $this->resource;
        return [
            'ORG_ID'  => $this->org_id,
            'ORG_NAME' => $this->org_name,
            'ORG_ABBR' => $this->org_abbr,
            'ORG_EMAIL' => $this->org_email,
            'ORG_PHONE' => $this->org_phone,
            'ORG_WEBSITE' => $this->org_website,
            'ORG_STATUS' => $this->status,
        ];
    }
}

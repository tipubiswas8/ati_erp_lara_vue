<?php

namespace App\Http\Resources\Hr\Employee;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        return [
            'employe_id' => $this->employe_id,
            'efull_name' => $this->efull_name,
            'ofie_email' => $this->ofie_email,
            'omobile_no' => $this->omobile_no,
            'pres_addrs' => $this->pres_addrs,
            'astatus_fg' => $this->astatus_fg,
            'user_name' => $this->user ? $this->user->user_name : null,
        ];
    }
}

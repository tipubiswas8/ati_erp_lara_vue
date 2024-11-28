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
        // return parent::toArray($request);
        // return $this->resource;
        return [
            'EMPLOYEE_ID' => $this->employee_id,
            'EFULL_NAME' => $this->efull_name,
            'OFIE_EMAIL' => $this->ofie_email,
            'OMOBILE_NO' => $this->omobile_no,
            'PRES_ADDRS' => $this->pres_addrs,
            'COMPANY_ID' => $this->company_id,
            'ASTATUS_FG' => $this->astatus_fg,
            'USER_NAME' => $this->user ? $this->user->user_name : null,
        ];
    }
}

<?php

namespace App\Http\Resources\Sa\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'employe_id' => $this->employee->employe_id,
            'efull_name' => $this->employee->efull_name,
            'deprtmn_id' => $this->employee->deprtmn_id,
            'desgton_id' => $this->employee->desgton_id,
            'biometicid' => $this->employee->biometicid,
            'ofie_email' => $this->employee->ofie_email,
            'omobile_no' => $this->employee->omobile_no,
            'pmobile_no' => $this->employee->pmobile_no,
            'user_name' => $this->user_name,
            'emp_id' => $this->emp_id,
            'role_id' => $this->role_id,
            'status' => $this->status,
            'password' => $this->password,
            'emoji' => $this->emoji
        ];
    }

}

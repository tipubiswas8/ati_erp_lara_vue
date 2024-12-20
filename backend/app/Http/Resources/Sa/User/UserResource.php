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
            'employee_id' => $this->employee->employee_id,
            'en_full_name' => $this->employee->en_full_name,
            'deprtmn_id' => $this->employee->deprtmn_id,
            'desgton_id' => $this->employee->desgton_id,
            'biometicid' => $this->employee->biometicid,
            'ofie_email' => $this->employee->ofie_email,
            'ppo_hemail' => $this->employee->ppo_hemail,
            'omobile_no' => $this->employee->omobile_no,
            'pmobile_no' => $this->employee->pmobile_no,
            'user_id' => $this->id,
            'user_name' => $this->user_name,
            'emp_id' => $this->emp_id,
            'role_id' => (array) $this->role_id,
            'status' => $this->status,
            'password' => $this->password,
            'emoji' => $this->emoji
        ];
    }

}

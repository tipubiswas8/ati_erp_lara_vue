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
            'EN_FULL_NAME' => $this->en_full_name,
            'OFIE_EMAIL' => $this->ofie_email,
            'OMOBILE_NO' => $this->omobile_no,
            'PRES_ADDRS' => $this->pres_addrs,
            'ASTATUS_FG' => $this->astatus_fg,
            'ORG_ID' => $this->organization ? $this->organization->org_id : null,
            'ORG_NAME' => $this->organization ? $this->organization->org_name : null,
            'ORG_ABBR' => $this->organization ? $this->organization->org_abbr : null,
            'DEPT_ID' => $this->department?->dept_id,
            'DEPT_NAME' => $this->department?->dept_name,
            'DEPT_ABBR' => $this->department?->dept_abbr,
            'DESIG_ID' => $this->designationTable?->desig_id,
            'DESIG_NAME' => $this->designationTable?->desig_name,
            'DESIG_ABBR' => $this->designationTable?->desig_abbr,
            'USER_ID' => $this->user ? $this->user->id : null,
            'USER_NAME' => $this->user ? $this->user->user_name : null,
        ];
    }
}

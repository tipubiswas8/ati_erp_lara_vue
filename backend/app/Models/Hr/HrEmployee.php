<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sa\SaUser;

// use Illuminate\Database\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\Model;

if (env('USE_MONGODB', false)) {
    class Model extends \MongoDB\Laravel\Eloquent\Model {}
} else {
    class Model extends \Illuminate\Database\Eloquent\Model {}
}

class HrEmployee extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'employee_id'; // Set the primary key

    public function organization(): HasOne
    {
        return $this->hasOne(HrOrganization::class, 'org_id', 'org_id');
    }
    public function department(): HasOne
    {
        return $this->hasOne(HrDepartment::class, 'dept_id', 'dept_id');
    }
    public function designationTable(): HasOne
    {
        return $this->hasOne(HrDesignation::class, 'desig_id', 'desig_id');
    }
    public function user(): HasOne
    {
        return $this->hasOne(SaUser::class, 'emp_id', 'employee_id');
    }

    // public function user()
    // {
    //     return $this->belongsTo(SaUser::class, 'employee_id', 'emp_id');
    // }
}

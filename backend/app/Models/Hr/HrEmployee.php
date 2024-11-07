<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Sa\SaUser;

class HrEmployee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employe_id'; // Set the primary key

    public function user(): HasOne
    {
        return $this->hasOne(SaUser::class, 'emp_id', 'employe_id');
    }

    // public function user()
    // {
    //     return $this->belongsTo(SaUser::class, 'employe_id', 'emp_id');
    // }
}

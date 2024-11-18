<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

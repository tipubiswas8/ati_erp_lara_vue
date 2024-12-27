<?php

namespace App\Models\Sa;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Hr\HrOrganization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use Illuminate\Database\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\Model;

if (env('USE_MONGODB', false)) {
    class Model extends \MongoDB\Laravel\Eloquent\Model {}
} else {
    class Model extends \Illuminate\Database\Eloquent\Model {}
}

class SaRole extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'org_id', 'created_by'];

    public function permissions()
    {
        return $this->belongsToMany(SaPermission::class)
            ->withPivot('created_by')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(SaUser::class, 'sa_users');
    }

    public function organization() : BelongsTo
    {
        return $this->belongsTo(HrOrganization::class, 'org_id', 'org_id');
    }

    // public function organization(): HasOne
    // {
    //     return $this->hasOne(HrOrganization::class, 'org_id', 'org_id');
    // }

}

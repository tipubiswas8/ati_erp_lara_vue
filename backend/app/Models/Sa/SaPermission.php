<?php

namespace App\Models\Sa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Database\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\Model;

if (env('USE_MONGODB', false)) {
    class BaseModel extends \MongoDB\Laravel\Eloquent\Model {}
} else {
    class BaseModel extends \Illuminate\Database\Eloquent\Model {}
}

class SaPermission extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'org_id', 'created_by'];

    public function roles()
    {
        return $this->belongsToMany(SaRole::class)
            ->withPivot('created_by')
            ->withTimestamps();
    }
}


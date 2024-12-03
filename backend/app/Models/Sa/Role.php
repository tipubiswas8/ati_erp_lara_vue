<?php

namespace App\Models\Sa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Database\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\Model;

if (env('USE_MONGODB', false)) {
    class Model extends \MongoDB\Laravel\Eloquent\Model {}
} else {
    class Model extends \Illuminate\Database\Eloquent\Model {}
}

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'org_id', 'created_by'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)
            ->withPivot('created_by')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(SaUser::class, 'sa_users');
    }
}

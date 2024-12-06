<?php

namespace App\Models\Sa;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Hr\HrEmployee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use Illuminate\Foundation\Auth\User as Authenticatable;
// use MongoDB\Laravel\Auth\User as Authenticatable;

if (env('USE_MONGODB', false)) {
    class Authenticatable2 extends \MongoDB\Laravel\Auth\User {}
} else {
    class Authenticatable2 extends \Illuminate\Foundation\Auth\User {}
}

class SaUser extends Authenticatable2
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'org_id',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(HrEmployee::class, 'emp_id', 'employee_id');
    }

    // public function employee()
    // {
    //     return $this->hasOne(HrEmployee::class, 'employee_id', 'emp_id');
    // }

    public function roles()
    {
        return $this->belongsToMany(SaRole::class, 'sa_users');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    public function assignRole($role)
    {
        $this->roles()->attach($role);
    }
}

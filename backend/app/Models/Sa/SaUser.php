<?php

namespace App\Models\Sa;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Hr\HrEmployee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use Illuminate\Foundation\Auth\User as Authenticatable;
// use MongoDB\Laravel\Auth\User as Authenticatable;

if (env('USE_MONGODB', false)) {
    class Authenticatable extends \MongoDB\Laravel\Auth\User {}
} else {
    class Authenticatable extends \Illuminate\Foundation\Auth\User {}
}

class SaUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        return $this->belongsTo(HrEmployee::class, 'emp_id', 'employe_id');
    }

    // public function employee()
    // {
    //     return $this->hasOne(HrEmployee::class, 'employe_id', 'emp_id');
    // }
}

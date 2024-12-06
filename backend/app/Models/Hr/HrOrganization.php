<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sa\SaUser;

// use Illuminate\Database\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\Model;

if (env('USE_MONGODB', false)) {
    class BaseModel extends \MongoDB\Laravel\Eloquent\Model {}
} else {
    class BaseModel extends \Illuminate\Database\Eloquent\Model {}
}

class HrOrganization extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'org_id';
    public $incrementing = false; // Since the primary key is not auto-incrementing
    protected $keyType = 'string'; // Primary key is a string

    protected $fillable = [
        'org_name',
        'org_abbr',
        'org_slogan',
        'org_details',
        'org_email',
        'org_phone',
        'org_website',
        'org_address',
        'org_logo',
        'created_by',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($organization) {
    //         // Generate custom ID format: ORGYYYYMMDD00001
    //         $currentDate = now()->format('Ymd');
    //         $latestOrg = self::where('org_id', 'like', "ORG$currentDate%")->orderBy('org_id', 'desc')->first();
    //         if ($latestOrg) {
    //             // Increment the numeric part of the ID
    //             $lastNumber = (int)substr($latestOrg->org_id, -5);
    //             $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    //         } else {
    //             $newNumber = '00001';
    //         }
    //         $organization->org_id = "ORG{$currentDate}{$newNumber}";
    //     });
    // }
}

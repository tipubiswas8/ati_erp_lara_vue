<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sa\SaUser;

// use Illuminate\Database\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\Model;

if (env('USE_MONGODB', false)) {
    class DeptModel extends \MongoDB\Laravel\Eloquent\Model {}
} else {
    class DeptModel extends \Illuminate\Database\Eloquent\Model {}
}

class HrDepartment extends DeptModel
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'dept_id';
    public $incrementing = false; // Since the primary key is not auto-incrementing
    protected $keyType = 'string'; // Primary key is a string
   

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($dept) {
    //         // Generate custom ID format: YYYYMMDDDPT000001
    //         $currentDate = now()->format('Ymd'); // Get the current date in YYYYMMDD format
    //         $deptPrefixAndCurrentDate = 'DPT' . $currentDate;
    //         $baseId = $deptPrefixAndCurrentDate . '000001';  // Default ID for the first record of the day
    //         // Get the latest `dept_id` in the database
    //         $latestDesig = self::max('dept_id');
    //         if ($latestDesig) {
    //             $latestSequencePart = (int)substr($latestDesig, -6); // Extract the sequence part (last 6 digits)
    //             $baseId =  $deptPrefixAndCurrentDate . str_pad($latestSequencePart + 1, 6, '0', STR_PAD_LEFT);
    //         }
    //         $dept->dept_id = $baseId;
    //     });
    // }
}

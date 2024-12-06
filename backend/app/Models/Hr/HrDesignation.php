<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Illuminate\Database\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\Model;

if (env('USE_MONGODB', false)) {
    class DesigModel extends \MongoDB\Laravel\Eloquent\Model {}
} else {
    class DesigModel extends \Illuminate\Database\Eloquent\Model {}
}

class HrDesignation extends DesigModel
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'desig_id';
    public $incrementing = false; // Since the primary key is not auto-incrementing

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($designation) {
    //         // Generate custom ID format: YYYYMMDD000001
    //         $currentDate = now()->format('Ymd'); // Get the current date in YYYYMMDD format
    //         $baseId = $currentDate . '000001';  // Default ID for the first record of the day
    //         // Get the latest `desig_id` in the database
    //         $latestDesig = self::max('desig_id');
    //         if ($latestDesig) {
    //             $latestSequencePart = (int)substr($latestDesig, -6); // Extract the sequence part (last 6 digits)
    //             $baseId =  $currentDate . str_pad($latestSequencePart + 1, 6, '0', STR_PAD_LEFT);
    //         }
    //         $designation->desig_id = (int)$baseId; // Convert to integer if required
    //     });
    // }
}

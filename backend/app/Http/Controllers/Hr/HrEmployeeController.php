<?php

namespace App\Http\Controllers\Hr;

use App\Models\Hr\HrEmployee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\Hr\Employee\EmployeeResource;
use App\Models\Sa\SaUser;

class HrEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HrEmployee $hrEmployee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HrEmployee $hrEmployee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HrEmployee $hrEmployee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HrEmployee $hrEmployee)
    {
        //
    }

    public function allEmployee()
    {
        ini_set('memory_limit', '1024M');
        $startMicrotime = microtime(true); // Start time in seconds and microseconds
        $data = Redis::get('emp_data');
        $data = unserialize($data);
        $endMicrotime = microtime(true);
        $fetchDurationS = round($endMicrotime - $startMicrotime, 2);
        $fetchDurationMs = round(($endMicrotime - $startMicrotime) * 1000, 2);


        $response = [
            'status' => true,
            'message' => 'All employees fetched successfully',
            'start_time' => date('i:s:v', (int)$startMicrotime) . ':' . round(($startMicrotime - floor($startMicrotime)) * 1000),
            'end_time' => date('i:s:v', (int)$endMicrotime) . ':' . round(($endMicrotime - floor($endMicrotime)) * 1000),
            'fetch_duration_s' => $fetchDurationS,
            'fetch_duration_ms' => $fetchDurationMs,
            'data' => $data,
        ];
        return response()->json($response, 200);



        try {
            $startMicrotime = microtime(true); // Start time in seconds and microseconds
            $data_source = 'data from redis';

            try {
                // Attempt to get employee data from Redis
                $data = Redis::get('emp_data');
                if ($data) {
                    // Unserialize data from Redis
                    $data = unserialize($data);
                } else {
                    // If no data in Redis, fetch from database and cache in Redis
                    throw new Exception("Data not in Redis, fetching from database");
                }
            } catch (Exception $e) {
                // Redis unavailable or data not in cache, fetch from database
                $data_source = 'data from database';
                $employees = HrEmployee::take(20000)->get();
                $data = EmployeeResource::collection($employees);

                // Attempt to cache in Redis if available
                try {
                    Redis::set('emp_data', serialize($data));
                    Redis::expire('emp_data', 3600); // Set expiration to 1 hour
                } catch (Exception $redisError) {
                    // Log error if unable to cache in Redis
                    Log::error('Redis caching failed', ['error_message' => $redisError->getMessage()]);
                }
            }

            // End time calculation
            $endMicrotime = microtime(true);
            $fetchDurationS = round($endMicrotime - $startMicrotime, 2);
            $fetchDurationMs = round(($endMicrotime - $startMicrotime) * 1000, 2);

            $response = [
                'status' => true,
                'message' => 'All employees fetched successfully',
                'data_source' => $data_source,
                'start_time' => date('i:s:v', (int)$startMicrotime) . ':' . round(($startMicrotime - floor($startMicrotime)) * 1000),
                'end_time' => date('i:s:v', (int)$endMicrotime) . ':' . round(($endMicrotime - floor($endMicrotime)) * 1000),
                'fetch_duration_s' => $fetchDurationS,
                'fetch_duration_ms' => $fetchDurationMs,
                'data' => $data,
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            // General error handling
            $response = [
                'status' => false,
                'message' => $e->getMessage()
            ];
            Log::error('Unable to load data', [
                'error_message' => $e->getMessage(),
            ]);
            return response()->json($response, 500);
        }

        // try {
        //     $employees = HrEmployee::with('user')->paginate(10);
        //     // Prepare the data using EmployeeResource
        //     $response = [
        //         'status' => true,
        //         'message' => 'All emplyee fetch',
        //         'data' => EmployeeResource::collection($employees),
        //         'meta' => [
        //             'current_page' => $employees->currentPage(),
        //             'last_page' => $employees->lastPage(),
        //             'per_page' => $employees->perPage(),
        //             'total' => $employees->total(),
        //         ],
        //     ];
        //     return response()->json($response, 200);
        // } catch (Exception $e) {
        //     $response = [
        //         'status' => false,
        //         'message' => $e->getMessage()
        //     ];
        //     Log::error('Unable to load data', [
        //         'error_message' => $e->getMessage(),
        //     ]);
        //     return response()->json($response, 500);
        // }
    }
}

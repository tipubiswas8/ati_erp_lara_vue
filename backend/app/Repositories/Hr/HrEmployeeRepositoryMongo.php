<?php

namespace App\Repositories\Hr;

use App\Interface\Hr\HrEmployeeInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\Hr\Employee\EmployeeResource;
use App\Models\Hr\HrEmployee;

class HrEmployeeRepositoryMongo implements HrEmployeeInterface
{
    public function index()
    {
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
    public function create() {}
    public function store(Request $requestData) {}
    public function show(HrEmployee $hrEmployee) {}
    public function edit(HrEmployee $hrEmployee) {}
    public function update(Request $requestData, HrEmployee $hrEmployee) {}

    public function destroy(HrEmployee $hrEmployee)
    {
        $employee = HrEmployee::where('employee_id', (int) ($hrEmployee->id))->first();
        if ($employee) {
            try {
                // $delete = HrEmployee::destroy($employee->employee_id);
                $delete = $employee->delete();
                if ($delete) {
                    Log::info('Employee delete successful', [
                        'employee' => $employee
                    ]);
                    return response()->json(['status' => true, 'message' => 'Employee Deleted Successfully!'], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Unable to delete employee, please try again later'], 507);
                }
            } catch (\Exception $e) {
                Log::error('Unable to delete employee, please try again!', [
                    'employee' => $employee,
                    'error_message' => $e->getMessage(),
                ]);
                return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
            return response()->json(['status' => false, 'message' => 'Employee not found'], 404);
        }
    }

    public function restore()
    {
        // $restoredEmployee = HrEmployee::onlyTrashed()->get();
        $restoredEmployee = HrEmployee::onlyTrashed()->restore();

        if ($restoredEmployee) {
            try {
                Log::info('Employee restore successful', [
                    'employee' => $restoredEmployee
                ]);
                return response()->json(['status' => true, 'message' => 'Employee Restore Successfully!', 'employee' => $restoredEmployee], 200);
            } catch (\Exception $e) {
                Log::error('Unable to restore employee, please try again!', [
                    'employee' => $restoredEmployee,
                    'error_message' => $e->getMessage(),
                ]);
                return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
            return response()->json(['status' => false, 'message' => 'No deleted employee found'], 404);
        }
    }

    public function allEmployee()
    {

        // // block-1
        // return response()->json(['all data' => HrEmployee::with('user')->paginate(10)], 200);

        // ini_set('memory_limit', '1024M');
        // $startMicrotime = microtime(true); // Start time in seconds and microseconds
        // $data = Redis::get('emp_data');
        // $data = unserialize($data);
        // $endMicrotime = microtime(true);
        // $fetchDurationS = round($endMicrotime - $startMicrotime, 2);
        // $fetchDurationMs = round(($endMicrotime - $startMicrotime) * 1000, 2);


        // $response = [
        //     'status' => true,
        //     'message' => 'All employees fetched successfully',
        //     'start_time' => date('i:s:v', (int)$startMicrotime) . ':' . round(($startMicrotime - floor($startMicrotime)) * 1000),
        //     'end_time' => date('i:s:v', (int)$endMicrotime) . ':' . round(($endMicrotime - floor($endMicrotime)) * 1000),
        //     'fetch_duration_s' => $fetchDurationS,
        //     'fetch_duration_ms' => $fetchDurationMs,
        //     'data' => $data,
        // ];
        // return response()->json($response, 200);


        
        // block-2
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
    }
}

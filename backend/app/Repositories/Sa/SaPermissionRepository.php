<?php

namespace App\Repositories\Sa;

use App\Interface\Sa\SaPermissionInterface;
use App\Models\Sa\SaPermission;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Sa\Permission\PermissionCollection;
use App\Http\Resources\Sa\Permission\PermissionResource;
use App\Models\Hr\HrOrganization;

class SaPermissionRepository implements SaPermissionInterface
{
    public function index()
    {
        try {
            return responseSuccess('All permission fetch', new PermissionCollection(SaPermission::with('organization')->where('status', 1)->get()), 200);
        } catch (Exception $e) {
            logError($e, 'Unable to load permission data');
            return handleException($e, 'Unable to load permission data', 500);
        }
    }

    public function create() {}

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'permission_name' => 'required|max:50',
                'org_for' => [
                    'max:16',
                    function ($attribute, $value, $fail) {
                        if (empty($value)) {
                            $fail('Please select organization first');
                        }
                    },
                ],
                'status' => 'nullable',
            ]);
            $createData['name'] = $validatedData['permission_name'];
            $createData['status'] = $validatedData['status'] ? 1 : 0;
            $createData['created_by'] = 1;
            if ($validatedData['org_for'] == 'all') {
                try {
                    $organizations = HrOrganization::pluck('org_id'); // Fetch only IDs

                    $existingPermission = SaPermission::where('name', $createData['name'])
                        ->whereIn('org_id', $organizations)
                        ->pluck('org_id'); // Fetch IDs of already existing permissions

                    $newOrganizations = $organizations->diff($existingPermission); // Find IDs not in existing permissions
                    $data = $newOrganizations->map(function ($orgId) use ($createData) {
                        return [
                            'name' => $createData['name'],
                            'org_id' => $orgId,
                            'status' => $createData['status'],
                            'created_by' => $createData['created_by'],
                            'created_at' => now(),
                        ];
                    })->toArray();
                    $insert = false;
                    if (!empty($data)) {
                        $insert = SaPermission::insert($data);
                    }
                    // Retrieve all the inserted permissions
                    $lastInsertedPermissions = SaPermission::where('name', $createData['name'])
                        ->whereIn('org_id', $newOrganizations)
                        ->get();
                    if ($insert) {
                        logInfo('Permission Created Successfully!', $lastInsertedPermissions);
                        return responseSuccess('Permission Created Successfully!', new PermissionCollection($lastInsertedPermissions), 201);
                    } else {
                        logInfo('Permission Already Exist!, Please Try Another Permission');
                        return responseSuccess('Permission Already Exist!, Please Try Another Permission', [], 409);
                    }
                } catch (\Exception $e) {
                    logError($e, 'Permission Create Failed', $request->all());
                    return handleException($e, 'Permission Create Failed', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } elseif ($validated['org_for'] == 'active') {
                try {
                    $organizations = HrOrganization::where('status', 1)->pluck('org_id'); // Fetch only IDs

                    $existingPermission = SaPermission::where('name', $validated['name'])
                        ->whereIn('org_id', $organizations)
                        ->pluck('org_id'); // Fetch IDs of already existing permissions

                    $newOrganizations = $organizations->diff($existingPermission); // Find IDs not in existing permissions
                    $data = $newOrganizations->map(function ($orgId) use ($validated) {
                        return [
                            'name' => $validated['name'],
                            'org_id' => $orgId,
                            'status' => $validated['status'] ? 1 : 0,
                            'created_by' => $validated['created_by'],
                            'created_at' => now(),
                        ];
                    })->toArray();

                    $insert = false;
                    if (!empty($data)) {
                        $insert = SaPermission::insert($data);
                    }

                    // Retrieve all the inserted permissions
                    $lastInsertedPermissions = SaPermission::where('name', $validated['name'])
                        ->whereIn('org_id', $newOrganizations)
                        ->get();
                    if ($insert) {
                        logInfo('Permission Created Successfully!', $lastInsertedPermissions);
                        return responseSuccess('Permission Created Successfully!', PermissionResource::collection($lastInsertedPermissions), 201);
                    } else {
                        logInfo('Permission Already Exist!, Please Try Another Permission');
                        return responseSuccess('Permission Already Exist!, Please Try Another Permission', [], 409);
                    }
                } catch (\Exception $e) {
                    logError($e, 'Permission Create failed', $request->all());
                    return handleException($e, 'Permission Create failed', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else if ($validated['org_for'] == 'specific') {
                try {
                    $organizations = collect($request->org_id);

                    $existingPermission = SaPermission::where('name', $validated['name'])
                        ->whereIn('org_id', $organizations)
                        ->pluck('org_id'); // Fetch IDs of already existing permissions

                    $newOrganizations = $organizations->diff($existingPermission); // Find IDs not in existing permissions
                    $data = $newOrganizations->map(function ($orgId) use ($validated) {
                        return [
                            'name' => $validated['name'],
                            'org_id' => $orgId,
                            'status' => $validated['status'] ? 1 : 0,
                            'created_by' => $validated['created_by'],
                            'created_at' => now(),
                        ];
                    })->toArray();

                    $insert = false;
                    if (!empty($data)) {
                        $insert = SaPermission::insert($data);
                    }

                    // Retrieve all the inserted permissions
                    $lastInsertedPermissions = SaPermission::where('name', $validated['name'])
                        ->whereIn('org_id', $newOrganizations)
                        ->get();
                    if ($insert) {
                        logInfo('Permission Created Successfully!', $lastInsertedPermissions);
                        return responseSuccess('Permission Created Successfully!', PermissionResource::collection($lastInsertedPermissions), 201);
                    } else {
                        logInfo('Permission Already Exist!, Please Try Another Permission');
                        return responseSuccess('Permission Already Exist!, Please Try Another Permission', [], 409);
                    }
                } catch (\Exception $e) {
                    logError($e, 'Permission Create failed', $request->all());
                    return handleException($e, 'Permission Create failed', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        } catch (ValidationException $e) {
            logWarning('Validation error during create permission', $e->errors(), $request->all());
            return handleValidationError($e->errors(), 'Permission Create failed', 422);
        }
    }

    public function show(SaPermission $permission)
    {
        try {
            return response()->json(new PermissionResource($permission), Response::HTTP_OK);
        } catch (Exception $e) {
            logError($e, 'Unable to load permission data');
            return handleException($e, 'Unable to permission role data', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(SaPermission $permission) {}

    public function update(Request $request, SaPermission $permission)
    {
        $user = SaPermission::find($request->id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404); // 404 Not Found
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits_between:10,11', // Correct phone validation
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
            'status' => 'required',
        ];

        // Validate the request data against the dynamic rules
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::warning('Validation error during user registration', [
                'errors' => $validator->errors(),
                'request_data' => $request->all(),
            ]);

            // Return immediately if validation fails
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400); // 400 Bad Request for validation errors
            // Get the validated data
        }
        $validated = $validator->validated();
        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->address = $validated['address'] ?? $user->address; // Keep existing address if not provided
            $user->password = $validated['password'];
            $user->status = $validated['status'];
            $user->save();
            Log::info('User Update Successfully', [
                'user' => $user
            ]);
            return response()->json(['status' => true, 'message' => 'User Update Successfully!', 'user' => $user], 204);
        } catch (Exception $e) {
            Log::error('User Update Failed', [
                'user' => $user,
                'error_message' => $e->getMessage(),
            ]);
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function status(Request $request)
    {
        $user = SaPermission::find($request->id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found!'], 404);
        }
        try {
            $user->status = !$request->status;
            $update = $user->save();
            if ($update) {
                Log::alert('User Status Updated Successfully', [
                    'user' => $user,
                    'status' => $user->status
                ]);
                return response()->json(['status' => true, 'message' => 'User Status Updated Successfully!', 'data' => $user], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Unable to update status, please try again later'], 507);
            }
        } catch (Exception $e) {
            Log::error('Unable to update user status, please try again!', [
                'user' => $user,
                'error_message' => $e->getMessage(),
            ]);
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(SaPermission $permission)
    {
        $permission = SaPermission::find($permission->id);
        if ($permission) {
            try {
                // Soft delete the record
                // $delete = SaPermission::destroy($permission->id);
                // $delete = $permission->delete();
                $permission->deleted_by = '100';
                // $delete = $permission->save() && $permission->delete(); 
                $delete = $permission->save() && SaPermission::destroy($permission->id);
                if ($delete) {
                    logInfo('Permission Deleted Successfully!', $permission);
                    return responseSuccess('Permission Deleted Successfully!');
                } else {
                    return responseFailed('Unable to delete permission, please try again later');
                }
            } catch (\Exception $e) {
                logError($e, 'Unable to delete permission, please try again!', $permission);
                return handleException($e, 'Unable to delete permission, please try again!');
            }
        } else {
            return responseNotFound('Permission not found');
        }
    }
}

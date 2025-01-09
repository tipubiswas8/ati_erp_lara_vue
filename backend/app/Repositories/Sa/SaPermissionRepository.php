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
                        return responseSuccess('Permission Created Successfully!', new PermissionCollection($lastInsertedPermissions), Response::HTTP_CREATED);
                    } else {
                        logInfo('Permission Already Exist!, Please Try Another Permission');
                        return responseSuccess('Permission Already Exist!, Please Try Another Permission', [], Response::HTTP_CONFLICT);
                    }
                } catch (\Exception $e) {
                    logError($e, 'Permission Create Failed', $request->all());
                    return handleException($e, 'Permission Create Failed', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } elseif ($validatedData['org_for'] == 'active') {
                try {
                    $organizations = HrOrganization::where('status', 1)->pluck('org_id'); // Fetch only IDs

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
                        return responseSuccess('Permission Created Successfully!', PermissionResource::collection($lastInsertedPermissions), Response::HTTP_CREATED);
                    } else {
                        logInfo('Permission Already Exist!, Please Try Another Permission');
                        return responseSuccess('Permission Already Exist!, Please Try Another Permission', [], Response::HTTP_CONFLICT);
                    }
                } catch (\Exception $e) {
                    logError($e, 'Permission Create failed', $request->all());
                    return handleException($e, 'Permission Create failed', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else if ($validatedData['org_for'] == 'specific') {
                try {
                    $organizations = collect($request->org_id);

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
                        return responseSuccess('Permission Created Successfully!', new PermissionCollection($lastInsertedPermissions), Response::HTTP_CREATED);
                    } else {
                        logInfo('Permission Already Exist!, Please Try Another Permission');
                        return responseSuccess('Permission Already Exist!, Please Try Another Permission', [], Response::HTTP_CONFLICT);
                    }
                } catch (\Exception $e) {
                    logError($e, 'Permission Create failed', $request->all());
                    return handleException($e, 'Permission Create failed', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        } catch (ValidationException $e) {
            logWarning('Validation error during create permission', $e->errors(), $request->all());
            return handleValidationError($e->errors(), 'Permission Create failed', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(SaPermission $permission) {}

    public function edit(SaPermission $permission) {}

    public function update(Request $request, SaPermission $permission)
    {
        $requestData = $request->only(['permission_name', 'org_name', 'permission_id', 'status']);
        $permission = SaPermission::find($requestData['permission_id']);
        if (!$permission) {
            return response()->json(['status' => false, 'message' => 'Permission not found'], Response::HTTP_NOT_FOUND); // 404 Not Found
        }

        $rules = [
            'permission_name' => 'required|string|max:20',
            'org_name' => 'required|string|max:16'
        ];

        // Validate the request data against the dynamic rules
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            logWarning('Validation error during update permission', $validator->errors(), $requestData);
            return handleValidationError($validator->errors(), 'Permission Update failed', Response::HTTP_UNPROCESSABLE_ENTITY);
            // 422 server was unable to process the request because it contains invalid data.
        }
        $validated = $validator->validated();
        try {
            $permission->name = $validated['permission_name'];
            $permission->org_id = $validated['org_name'];
            $permission->status = $requestData['status'] ? 1 : 0;
            $permission->save();
            logInfo('Permission Update Successfully!', $permission);
            return responseSuccess('Permission Update Successfully!', new PermissionResource($permission), Response::HTTP_OK);
        } catch (Exception $e) {
            logError($e, 'Permission Update Failed!', $requestData);
            return handleException($e, 'Permission Update Failed!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function status()
    {
        $request = request();
        $permission = SaPermission::find($request->id);
        if (!$permission) {
            return response()->json(['status' => false, 'message' => 'Permission not found!'], 404);
        }
        try {
            $permission->status = $request->status;
            $update = $permission->save();
            if ($update) {
                logAlert('Permission Status Updated Successfully!', $permission);
                return responseSuccess('Permission Status Updated Successfully!', $permission, 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Unable to update status, please try again later'], 507);
            }
        } catch (Exception $e) {
            logError($e, 'Unable to update permission status, please try again!', $permission);
            return handleException($e, 'Unable to update permission status, please try again!');
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

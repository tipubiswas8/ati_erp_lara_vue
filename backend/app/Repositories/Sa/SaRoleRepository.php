<?php

namespace App\Repositories\Sa;

use App\Interface\Sa\SaRoleInterface;
use App\Models\Sa\SaRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Sa\Role\RoleCollection;
use App\Http\Resources\Sa\Role\RoleResource;
use App\Models\Hr\HrOrganization;

class SaRoleRepository implements SaRoleInterface
{
    public function index()
    {
        try {
            return responseSuccess('All role fetch', RoleResource::collection(SaRole::whereHas('organization', function ($query) {
                $query->where('status', 1);
            })->with('organization')->get()), Response::HTTP_OK);
        } catch (Exception $e) {
            logError($e, 'Unable to load role data');
            return handleException($e, 'Unable to load role data', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create() {}
    public function store(Request $request)
    {
        $validator = Validator::make(
            [
                'name' => $request->role_name,
                'org_for' => $request->org_for,
                'status' => $request->status,
                'created_by' => 1
            ],
            [
                'name' => [
                    'max:20',
                    function ($attribute, $value, $fail) {
                        if (empty($value)) {
                            $fail('The role name is required.');
                        }
                    },
                ],
                'org_for' => 'required',
                'status' => 'nullable',
                'created_by' => 'required',
            ]
        );

        if ($validator->fails()) {
            logWarning('Validation error during create role', $validator->errors(), $request->all());
            return handleValidationError($validator->errors(), 'Role Create failed', 422);
        }

        $validated = $validator->validated();

        if ($validated['org_for'] == 'all') {
            try {
                $organizations = HrOrganization::pluck('org_id'); // Fetch only IDs

                $existingRoles = SaRole::where('name', $validated['name'])
                    ->whereIn('org_id', $organizations)
                    ->pluck('org_id'); // Fetch IDs of already existing roles

                $newOrganizations = $organizations->diff($existingRoles); // Find IDs not in existing roles
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
                    $insert = SaRole::insert($data);
                }

                // Retrieve all the inserted roles
                $lastInsertedRoles = SaRole::where('name', $validated['name'])
                    ->whereIn('org_id', $newOrganizations)
                    ->get();
                if ($insert) {
                    logInfo('Role Created Successfully!', $lastInsertedRoles);
                    return responseSuccess('Role Created Successfully!', RoleResource::collection($lastInsertedRoles), 201);
                } else {
                    logInfo('Role Already Exist!, Please Try Another Role');
                    return responseSuccess('Role Already Exist!, Please Try Another Role', [], 409);
                }
            } catch (\Exception $e) {
                logError($e, 'Role Create Failed', $request->all());
                return handleException($e, 'Role Create Failed', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } elseif ($validated['org_for'] == 'active') {
            try {
                $organizations = HrOrganization::where('status', 1)->pluck('org_id'); // Fetch only IDs

                $existingRoles = SaRole::where('name', $validated['name'])
                    ->whereIn('org_id', $organizations)
                    ->pluck('org_id'); // Fetch IDs of already existing roles

                $newOrganizations = $organizations->diff($existingRoles); // Find IDs not in existing roles
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
                    $insert = SaRole::insert($data);
                }

                // Retrieve all the inserted roles
                $lastInsertedRoles = SaRole::where('name', $validated['name'])
                    ->whereIn('org_id', $newOrganizations)
                    ->get();
                if ($insert) {
                    logInfo('Role Created Successfully!', $lastInsertedRoles);
                    return responseSuccess('Role Created Successfully!', RoleResource::collection($lastInsertedRoles), 201);
                } else {
                    logInfo('Role Already Exist!, Please Try Another Role');
                    return responseSuccess('Role Already Exist!, Please Try Another Role', [], 409);
                }
            } catch (\Exception $e) {
                logError($e, 'Role Create failed', $request->all());
                return handleException($e, 'Role Create failed', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else if ($validated['org_for'] == 'specific') {

            try {
                $organizations = collect($request->org_id);

                $existingRoles = SaRole::where('name', $validated['name'])
                    ->whereIn('org_id', $organizations)
                    ->pluck('org_id'); // Fetch IDs of already existing roles

                $newOrganizations = $organizations->diff($existingRoles); // Find IDs not in existing roles
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
                    $insert = SaRole::insert($data);
                }

                // Retrieve all the inserted roles
                $lastInsertedRoles = SaRole::where('name', $validated['name'])
                    ->whereIn('org_id', $newOrganizations)
                    ->get();
                if ($insert) {
                    logInfo('Role Created Successfully!', $lastInsertedRoles);
                    return responseSuccess('Role Created Successfully!', RoleResource::collection($lastInsertedRoles), 201);
                } else {
                    logInfo('Role Already Exist!, Please Try Another Role');
                    return responseSuccess('Role Already Exist!, Please Try Another Role', [], 409);
                }
            } catch (\Exception $e) {
                logError($e, 'Role Create failed', $request->all());
                return handleException($e, 'Role Create failed', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function show(SaRole $role)
    {
        try {
            return response()->json(new RoleResource($role), Response::HTTP_OK);
        } catch (Exception $e) {
            logError($e, 'Unable to load role data');
            return handleException($e, 'Unable to load role data', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(SaRole $role) {}

    public function update(Request $request, SaRole $role)
    {
        $requestData = $request->only(['role_name', 'org_name', 'role_id', 'status']);
        $role = SaRole::find($requestData['role_id']);
        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404); // 404 Not Found
        }

        $rules = [
            'role_name' => 'required|string|max:20',
            'org_name' => 'required|string|max:16'
        ];

        // Validate the request data against the dynamic rules
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            logWarning('Validation error during update role', $validator->errors(), $requestData);
            return handleValidationError($validator->errors(), 'Role Update failed', 422);
            // 422 server was unable to process the request because it contains invalid data.
        }
        $validated = $validator->validated();
        try {
            $role->name = $validated['role_name'];
            $role->org_id = $validated['org_name'];
            $role->status = $requestData['status'] ? 1 : 0;
            $role->save();
            logInfo('Role Update Successfully!', $role);
            return responseSuccess('Role Update Successfully!', new RoleResource($role), 200);
        } catch (Exception $e) {
            logError($e, 'Role Update Failed!', $requestData);
            return handleException($e, 'Role Update Failed!', 500);
        }
    }

    public function status()
    {
        $request = request();
        $role = SaRole::find($request->id);
        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role not found!'], 404);
        }
        try {
            $role->status = $request->status;
            $update = $role->save();
            if ($update) {
                logAlert('Role Status Updated Successfully!', $role);
                return responseSuccess('Role Status Updated Successfully!', $role, 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Unable to update status, please try again later'], 507);
            }
        } catch (Exception $e) {
            logError($e, 'Unable to update role status, please try again!', $role);
            return handleException($e, 'Unable to update role status, please try again!');
        }
    }

    public function destroy(SaRole $role)
    {
        $role = SaRole::find($role->id);
        if ($role) {
            try {
                $delete = SaRole::destroy($role->id);
                // $delete = $role->delete(); 
                if ($delete) {
                    logInfo('Role Deleted Successfully!', $role);
                    return responseSuccess('Role Deleted Successfully!');
                } else {
                    return responseFailed('Unable to delete role, please try again later');
                }
            } catch (\Exception $e) {
                logError($e, 'Unable to delete role, please try again!', $role);
                return handleException($e, 'Unable to delete role, please try again!');
            }
        } else {
            return responseNotFound('Role not found');
        }
    }
}

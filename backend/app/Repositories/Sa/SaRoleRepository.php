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

class SaRoleRepository implements SaRoleInterface
{
    public function index()
    {
        try {
            return responseSuccess('All role fetch', RoleResource::collection(SaRole::all()), Response::HTTP_OK);
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
                'name' => $request->name,
                'created_by' => 1
            ],
            [
                'name' => 'required|unique:roles|max:50',
                'created_by' => 'required',
            ]
        );

        if ($validator->fails()) {
            logWarning('Validation error during create role', $validator->errors(), $request->all());
            return handleValidationError($validator->errors(), 'Role Create failed', 422);
        }

        $validated = $validator->validated();
        try {
            $role = SaRole::create($validated);
            logInfo('Role Created Successfully!', $role);
            responseSuccess('Role Created Successfully!', new RoleResource($role), 201);
        } catch (\Exception $e) {
            logError($e, 'Role Create failed', $request->all());
            return handleException($e, 'Role Create failed', Response::HTTP_INTERNAL_SERVER_ERROR);
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
        $user = SaRole::find($request->id);
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
        $user = SaRole::find($request->id);
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

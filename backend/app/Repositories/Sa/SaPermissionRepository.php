<?php

namespace App\Repositories\Sa;

use App\Interface\Sa\SaPermissionInterface;
use App\Models\Sa\SaPermission;
use App\Models\Sa\SaUser;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Sa\Permission\PermissionCollection;
use App\Http\Resources\Sa\Permission\PermissionResource;

class SaPermissionRepository implements SaPermissionInterface
{
    public function index()
    {
        try {
            return response()->json(new PermissionCollection(SaPermission::all()), Response::HTTP_OK);
        } catch (Exception $e) {
            logError($e, 'Unable to load role data');
            return handleException($e, 'Unable to load role data', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create() {}

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:permissions|max:50'
            ]);
            // Validation passed
            try {
                $validatedData['created_by'] = 1;
                $permission = SaPermission::create($validatedData);
                logInfo('Permission Created Successfully!', $permission);
                responseSuccess('Permission Created Successfully!', new PermissionResource($permission), 201);
            } catch (\Exception $e) {
                logError($e, 'Permission Create failed', $request->all());
                return handleException($e, 'Permission Create failed', 500);
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
                // $delete = Permission::destroy($permission->id);
                $delete = $permission->delete();
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

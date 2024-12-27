<?php

namespace App\Repositories\Sa;

use App\Interface\Sa\SaUserInterface;
use App\Models\Sa\SaUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Sa\User\UserResource;
use App\Http\Resources\Sa\User\UserCollection;
use App\Models\Hr\HrEmployee;

class SaUserRepositoryMongo implements SaUserInterface
{
    public function login(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'email' => ['required', 'max:50'],
                'password' => ['required', 'string', 'min:6'],
            ]);

            $user_email = $validatedData['email'];
            $user_password = $validatedData['password'];

            // Attempt to find the employee using the email
            $employee = HrEmployee::where('ofie_email', $user_email)->first();

            // Initialize the credential array
            $credential = [];

            if ($employee) {
                // Use employee ID to create credentials
                $credential = [
                    'emp_id' => $employee->employee_id,
                    'password' => $user_password,
                ];
            } else {
                // If no employee found, check using mobile number
                $employee = HrEmployee::where('omobile_no', $user_email)->first();
                if ($employee) {
                    $userId = SaUser::where('emp_id', $employee->employee_id)->select('id')->first()->id;
                    if ($userId) {
                        $credential = [
                            'id' => $userId,
                            'password' => $user_password,
                        ];
                    }
                }
            }

            if (
                Auth::attempt($credential) ||
                Auth::attempt(['user_name' => $user_email, 'password' => $user_password])
            ) {
                try {
                    if (Auth::check()) {
                        $user = SaUser::find(Auth::id());
                        $token = $user->createToken('Token Name')->accessToken;
                        Log::notice('Token generete successfully', [
                            'user' => Auth::user(),
                            'token' => $token,
                        ]);
                        return response()->json(['status' => true, 'user' => Auth::user(), 'message' => 'Token generete successfully', 'token' => $token], 200);
                    } else {
                        return response()->json(['status' => false, 'message' => 'Unable to login, please try again'], 507);
                    }
                } catch (Exception $e) {
                    Log::error('Unable to generete token', [
                        'user' => Auth::user(),
                        'error_message' => $e->getMessage(),
                    ]);
                    return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Unable to login, please check your credentials'], 401); // 401 Unauthorized
            }
        } catch (Exception $e) {
            Log::error('Login failed', [
                'error_message' => $e->getMessage(),
            ]);
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function registration(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits_between:10,11', // Correct phone validation
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
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
        }

        // Get the validated data
        $validated = $validator->validated();
        $validated['status'] = 1;
        try {
            $user = SaUser::create($validated);
            Log::info('User registration successful', [
                'user' => $user
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User Registration Successfully!',
                'user' => new UserResource($user),
                // 'data' => new UserResource($user)
            ], 201); // 201 Created
        } catch (\Exception $e) {
            Log::error('User registration failed', [
                'error_message' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }
    public function update(Request $request)
    {
        $user = SaUser::find($request->id);
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
        $user = SaUser::find($request->id);
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

    public function destroy(Request $request)
    {
        $user = SaUser::where('emp_id', $request->id)->first();
        if ($user) {
            try {
                $delete = SaUser::destroy($user->id);
                // $delete = $user->delete(); 
                if ($delete) {
                    Log::info('User deleted successful', [
                        'user' => $user
                    ]);
                    return response()->json(['status' => true, 'message' => 'User Deleted Successfully!'], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Unable to delete user, please try again later'], 507);
                }
            } catch (\Exception $e) {
                Log::error('Unable to delete user, please try again!', [
                    'user' => $user,
                    'error_message' => $e->getMessage(),
                ]);
                return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }
        }
        return response()->json(['status' => false, 'message' => 'User not found'], 404);
    }

    public function users()
    {
        try {
            // return response()->json(new UserCollection(SaUser::with('employee')->get()), 200);
            return response()->json(new UserCollection(SaUser::all()), Response::HTTP_OK);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ];
            Log::error('Unable to load data', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // return response()->json($response, 500);
            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

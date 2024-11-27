<?php

namespace App\Repositories\Sa;

use App\Interface\Sa\SaUserInterface;
use App\Models\Sa\SaUser;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Sa\User\UserResource;
use App\Http\Resources\Sa\User\UserCollection;
use Illuminate\Support\Facades\Storage;
use App\Models\Hr\HrEmployee;

class SaUserRepository implements SaUserInterface
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
                        $userInfo = [
                            'user' => Auth::user(),
                            'token' => $token,
                        ];

                        $authUser = [
                            'user' => Auth::user(),
                            'token' => $token
                        ];

                        logNotice('Token generete successfully!', $userInfo);
                        responseSuccess('Token generete successfully!', $authUser, 200);
                    } else {
                        return response()->json(['status' => false, 'message' => 'Unable to login, please try again'], 507);
                    }
                } catch (Exception $e) {
                    logError($e, 'Unable to generete token', Auth::user());
                    return handleException($e, 'Unable to generete token', Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Unable to login, please check your credentials'], 401); // 401 Unauthorized
            }
        } catch (Exception $e) {
            logError($e, 'Login failed');
            return handleException($e, 'Login failed', 500);
        }
    }
    public function registration(Request $request)
    {
        // // $image = $_FILES['image'];
        // $dbImagePath = null;
        // if ($request->hasFile('image')) {
        //     $path = $request->image->path();
        //     $extension = $request->image->extension();
        //     $file = $request->file('image');
        //     $fullFileName = $file->getClientOriginalName();
        //     $extension = $file->getClientOriginalExtension();
        //     $unixTimestamp = time();
        //     $uniqueFileName = $unixTimestamp . '_' . $fullFileName;

        //     // // save file to storage folder
        //     // $store = $request->file('image')->storeAs('uploads/user/images', $uniqueFileName);
        //     // $dbImagePath = $uniqueFileName;

        //     // // save file to public folder with random unique name
        //     // $dbImagePath = $file->store('uploads/user/images', ['disk' => 'public_folder']);

        //     // // save file to public folder with random unique name
        //     // if (!Storage::disk('public_folder')->putFile ('uploads/user/images', $file)) {
        //     //     return false;
        //     // } 
        //     // $dbImagePath = Storage::disk('public_folder')->putFile ('uploads/user/images', $file);

        //     // // save file to public folder with unique name using unix timestamp
        //     if (!Storage::disk('public_folder')->putFileAs('uploads/user/images', $file, $uniqueFileName)) {
        //         return false;
        //     }
        //     $dbImagePath = $uniqueFileName;
        // }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sa_users,email',
            'phone' => 'required|digits_between:10,11', // Correct phone validation
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
        ];

        // Validate the request data against the dynamic rules
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            logWarning('Validation error during user registration', $validator->errors(), $request->all());
            return handleValidationError($validator->errors(), 'Validation error during user registration', 400);
        }

        // Get the validated data
        $validated = $validator->validated();
        $validated['status'] = 1;
        try {
            $user = SaUser::create($validated);
            logInfo('User registration successful!', $user);
            responseSuccess('User registration successful!', new UserResource($user), 201);
        } catch (\Exception $e) {
            logError($e, 'User registration failed!', $request->all());
            return handleException($e, 'User registration failed!', Response::HTTP_INTERNAL_SERVER_ERROR);
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
            logWarning('Validation error during user update', $validator->errors(), $request->all());
            return handleValidationError($validator->errors(), 'Validation error during user update', 400);
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
            logInfo('User Update Successfully!', $user);
            responseSuccess('User Update Successfully!', $user, 204);
        } catch (Exception $e) {
            logError($e, 'User Update Failed!', $request->all());
            return handleException($e, 'User Update Failed!');
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
                logAlert('User Status Updated Successfully!', $user);
                return responseSuccess('User Status Updated Successfully!', $user, 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Unable to update status, please try again later'], 507);
            }
        } catch (Exception $e) {
            logError($e, 'Unable to update user status, please try again!', $user);
            return handleException($e, 'Unable to update user status, please try again!');
        }
    }

    public function destroy(Request $request)
    {
        $user = SaUser::find($request->id);
        if ($user) {
            try {
                $delete = SaUser::destroy($user->id);
                // $delete = $user->delete(); 
                if ($delete) {
                    logInfo('User Deleted Successfully!', $user);
                    return responseSuccess('User Deleted Successfully!');
                } else {
                    return responseFailed('Unable to delete user, please try again later');
                }
            } catch (\Exception $e) {
                logError($e, 'Unable to delete user, please try again!', $user);
                return handleException($e, 'Unable to delete user, please try again!');
            }
        } else {
            return responseNotFound('User not found');
        }
    }

    public function users()
    {
        try {
            // return response()->json(new UserCollection(SaUser::with('employee')->get()), 200);
            return response()->json(new UserCollection(SaUser::with('employee')->get()), Response::HTTP_OK);
        } catch (Exception $e) {
            logError($e, 'Unable to load user data');
            return handleException($e, 'Unable to load user data');
        }
    }
}

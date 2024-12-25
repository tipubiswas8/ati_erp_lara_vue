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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


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
            'user_name' => 'required|string|max:20|unique:sa_users,user_name',
            'email' => 'required|email|unique:sa_users,email',
            'emp_id' => 'required|digits_between:1,3',
            // 'emp_id' => 'required|integer|between:1,3',
            'org_id' => 'required|string',
            'role_id' => 'required|array',
            'status' => 'required|boolean',
            'password' => 'required|string|min:6|confirmed',
        ];

        // Validate the request data against the dynamic rules
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            logWarning('Validation error during user registration', $validator->errors(), $request->all());
            return handleValidationError($validator->errors(), 'Validation error during user registration', 400);
        }

        // Get the validated data
        $validated = $validator->validated();
        $validated['status'] = $validated['status'] ? 1 : 0;
        $validated['role_id'] = $validated['role_id'][0];

        try {
            DB::beginTransaction(); // Begin a transaction

            // Check if the user exists (based on emp_id)
            $user = SaUser::where('emp_id', $validated['emp_id'])->first();
            $operation = $user ? 'update' : 'create'; // Determine the operation

            // Dynamically set created_by or updated_by
            if ($operation === 'update') {
                $validated['updated_by'] = 2; // Existing user, set updated_by
            } else {
                $validated['created_by'] = 1; // New user, set created_by
            }

            // Update or Create the user
            $user = SaUser::updateOrCreate(
                ['emp_id' => $validated['emp_id']], // Search condition
                $validated // Data to update or create
            );

            DB::commit(); // Commit the transaction

            // Prepare response messages
            $message = $operation === 'update'
                ? 'User update successful!'
                : 'User registration successful!';

            // Log and respond
            logInfo($message, $user);
            return responseSuccess($message, new UserResource($user), $operation === 'update' ? 200 : 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error
            logError($e, 'User operation failed!', $request->all());
            return handleException($e, 'User operation failed!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // return response()->json($user);
    }
    public function update(Request $request)
    {
        $user = SaUser::find($request->id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404); // 404 Not Found
        }

        $requestAll = [
            // fpr user tablle
            'user_id' => $request->user_id,
            'user_name' => $request->user_name,
            'official_email' => $request->official_email,
            'role_name' => $request->role_name,
            'status' => $request->status,
            'old_password' => $request->old_password,
            'new_password' => $request->new_password,
            'new_password_confirmation' => $request->confirm_password,
            // for employee table
            'emp_id' => $request->emp_id,
            'emp_name' => $request->emp_name,
            'official_email' => $request->official_email,
            'personal_email' => $request->personal_email,
            'official_mob' => $request->official_mob,
            'personal_mob' => $request->personal_mob,
        ];

        $rules = [
            // for user tablle
            'user_id' => 'required',
            'user_name' => 'required|string|max:20',
            'official_email' => 'required|email',
            'role_name' => 'required|array',
            'status' => 'required|boolean',
            // for employee table
            'emp_id' => 'required',
            'emp_name' => 'required|string|max:20',
            'official_email' => 'required|email',
            'personal_email' => 'nullable|email',
            'official_mob' => ['required', 'regex:/^\d{10,11}$/'],
            // or 
            'personal_mob' => 'nullable|digits_between:10,11',
        ];

        $addNewPasswordRule = false;

        if (Hash::check($requestAll['old_password'], $user->password)) {
            $addNewPasswordRule = true;
        }

        if ($requestAll['old_password']) {
            $rules['old_password'] = [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The old password is incorrect.');
                    }
                },
            ];
            if ($addNewPasswordRule) {
                $rules['new_password'] = 'required|string|min:6|max:20|confirmed';
            }
        }

        if ($requestAll['new_password']) {
            if (!$requestAll['old_password']) {
                $rules['old_password'] = [
                    function ($attribute, $value, $fail) {
                        $fail('Please input old password first.');
                    }
                ];
            } elseif ($requestAll['old_password']) {
                $rules['old_password'] = [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('The old password is incorrect.');
                        }
                    },
                ];
            } else {
                $rules['new_password'] = 'required|string|min:6|max:20|confirmed';
            }
        }

        if ($requestAll['new_password_confirmation']) {
            if (!$requestAll['old_password']) {
                $rules['old_password'] = [
                    function ($attribute, $value, $fail) {
                        $fail('Please input old password first.');
                    }
                ];
            } elseif ($requestAll['old_password']) {
                $rules['old_password'] = [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('The old password is incorrect.');
                        }
                    },
                ];
            } else {
                $rules['new_password'] = 'required|string|min:6|max:20|confirmed';
            }

            if ($requestAll['new_password']) {
                if (!$requestAll['old_password']) {
                    $rules['old_password'] = [
                        function ($attribute, $value, $fail) {
                            $fail('Please input old password first.');
                        }
                    ];
                } elseif ($requestAll['old_password']) {
                    $rules['old_password'] = [
                        'nullable',
                        'string',
                        function ($attribute, $value, $fail) use ($user) {
                            if (!Hash::check($value, $user->password)) {
                                $fail('The old password is incorrect.');
                            }
                        },
                    ];
                } else {
                    $rules['new_password'] = 'required|string|min:6|max:20|confirmed';
                }
            }
        }

        // Validate the request data against the dynamic rules
        $validator = Validator::make($requestAll, $rules);

        if ($validator->fails()) {
            logWarning('Validation error during user update', $validator->errors(), $request->all());
            return handleValidationError($validator->errors(), 'Validation error during user update', 400);
        }

        $validated = $validator->validated();

        if (!isset($validated['new_password'])) {
            $validated['new_password'] = '';
        }

        $employee_id = $validated['emp_id'];
        $updated_by = $validated['updated_by'] = 4;

        try {
            DB::beginTransaction(); // Begin a transaction
            $user->user_name = $validated['user_name'];
            $user->email = $validated['official_email'];
            $user->role_id = $validated['role_name'][0];
            $user->password = $validated['new_password'];
            $user->status = $validated['status'] ? 1 : 0;
            $user->updated_by = $updated_by;
            $user->updated_at = Carbon::now();
            $user->save();

            $employee = HrEmployee::find($employee_id);
            $employee->en_full_name = $validated['emp_name'];
            $employee->ofie_email = $validated['official_email'];
            $employee->omobile_no = $validated['official_mob'];
            $employee->ppo_hemail = $validated['personal_email'];
            $employee->pmobile_no = $validated['personal_mob'];
            $employee->astatus_fg = $validated['status'] ? 1 : 0;
            $employee->updated_by = $updated_by;
            $employee->updated_at = Carbon::now();
            $employee->save();
            
            DB::commit();
            logInfo('User Update Successfully!', $employee);
            return responseSuccess('User Update Successfully!', new UserResource($user), 200);
        } catch (Exception $e) {
            DB::rollBack();
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
            $user->status = $request->status;
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

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credential = [
            'email' => $request->email,
            'password' => $request->password
        ];

        Auth::attempt($credential);
        if (Auth::check()) {
            $user = User::find(Auth::id());
            $token = $user->createToken('Access Token')->plainTextToken;
            return response()->json(['user' => Auth::user(), 'token' => $token]);
        } else {
            return response()->json(['status' => 300, 'message' => 'Unable to login, please try again']);
        }
    }

    public function registration(Request $request)
    {
        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->password = $request->password;
            $user->status = true;
            $user->save();
            return response()->json(['status' => 200, 'message' => 'User Registration Successfully!']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'User Resistration Failed!', 'error' => $e]);
        }
    }

    public function update(Request $request)
    {

        $user = User::find($request->id);
        if ($user) {
            try {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->address = $request->address;
                $user->save();
                return response()->json(['status' => 200, 'message' => 'User Update Successfully!']);
            } catch (Exception $e) {
                return response()->json(['status' => 500, 'message' => 'User Update Failed!', 'error' => $e]);
            }
        }
    }

    public function status(Request $request)
    {
        $user = User::find($request->id);
    
        if (!$user) {
            return response()->json(['status' => 404, 'message' => 'User not found!']);
        }
    
        try {
            // Toggle the status
            $user->status2 = !$request->status;
            $update = $user->save();
    
            if ($update) {
                return response()->json(['status' => 200, 'message' => 'User Status Updated Successfully!', 'success' => $user]);
            } else {
                return response()->json(['status' => 300, 'message' => 'Unable to update status', 'success' => ['status' => 0, 'name' => $user->name]]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Unable to update user status, please try again!', 'error' => $e->getMessage()]);
        }
    }
    

    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            try {
                $delete = User::destroy($user->id);
                if ($delete) {
                    return response()->json(['status' => 200, 'message' => 'User Deleted Successfully!']);
                } else {
                    return response()->json(['status' => 300, 'message' => 'Unable to delete user']);
                }
            } catch (Exception $e) {
                return response()->json(['status' => 500, 'message' => 'Unable to delete user, please try again!', 'error' => $e]);
            }
        }
    }

    public function users()
    {
        return response()->json(User::get());
    }
}

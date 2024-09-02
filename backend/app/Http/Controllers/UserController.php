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
            $user->save();
            return response()->json(['status' => 200, 'message' => 'User Registration Successfully!']);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'User Resistration Failed!', 'error' => $e]);
        }
    }

    public function users()
    {
        return response()->json(User::get());
    }
}

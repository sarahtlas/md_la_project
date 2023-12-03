<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Passport\Passport;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phonenumber' => 'required|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create the user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'token' => $user->createToken('secret')->plainTextToken, 
            'user' => $user,
      ]);
    }

public function login(Request $request)
{
  $atter = $request->validate([
        'phonenumber' => 'required',
        'password' => 'required|string',
  ]);
   if (auth()->attempt($request->only('phonenumber', 'password'))) {
    
         return response()->json([
            'token' => auth()->user()->createToken('secret')->plainTextToken,
             'user' => auth()->user(),
        ],200);
    }

   
    return response()->json(['message' => 'Invalid credentials'], 401);
}
public function logout()
{
    return response()->json([
        'message' =>auth()->user()->tokens()->delete(),
        'text' =>"done",  
    ], 200);
}
    public function getUser()
    {
        return response()->json([
            'user' => auth()->user(),
        ]);
    }

    // public function updateUserInfo(Request $request){
    //     $atter = $request->validate([
    //         ''
    //     ]);
    // }
}

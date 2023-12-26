<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register(Request $request)
    {
       $validator = Validator::make($request->all(), [
           'name' => 'required|string|max:255',
           'email' => 'required|string|email',
           'phone' => 'required|string|max:11|min:11',
           'password' => 'required|string|min:6',
       ]);
    
       if ($validator->fails()) {
           return response()->json(['errors' => $validator->errors()], 400);
       }
    
       $emailExists = User::where('email', $request->email)->exists();
    
       if ($emailExists) {
           return response()->json(['message' => 'This email is already in use'], 400);
       }
    
       try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
  
            return response()->json(['message' => 'User registered successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while registering the user'], 500);
        }

    }
    


    public function login(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
     
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
     
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('UserToken')->plainTextToken;
                return response()->json(['message' => 'User logged in successfully', 'access_token' => $token, 'user_name' => $user->name]);
            } else {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

        
    public function logout(Request $request)
    {
        try {
        $user = $request->user(); // Retrieve the authenticated user
    
        // Revoke the user's token
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    
        return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to logout'], 500);
        }
    }

}

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
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string|max:11|min:11',
            'password' => 'required|string|min:6',
        ]);
        dd($validatedData);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
        ]);
    
        // You can customize the response as needed
        return response()->json(['message' => 'User registered successfully']);
    }


    public function login(Request $request)
    {   
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
}

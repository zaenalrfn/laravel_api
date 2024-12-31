<?php

namespace App\Http\Controllers\Api;


// import model
use App\Models\User;

// import resource PostResource
use App\Http\Resources\AuthResource;

//import facade Validator
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate data
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|confirmed'
        ]);

        // Check validator
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 401);
        }

        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return response langsung tanpa menggunakan Resource
            return new AuthResource([
                'status' => true,
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error during registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // login
    public function login(Request $request)
    {
        // Validate data
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // Check validator
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 401);
        }

        try {

            if (!Auth::attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;


            // Return response langsung tanpa menggunakan Resource
            return new AuthResource([
                'status' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error during login',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

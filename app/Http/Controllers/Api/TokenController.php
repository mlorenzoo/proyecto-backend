<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rules;

class TokenController extends Controller
{

    public function user(Request $request)
    {
        $user = $request->user();
       
        return response()->json([
            "success" => true,
            "user"    => $user,
            "roles"   => [$user->role],
        ]);
    }

    public function register(Request $request)
    {
        // Validamos que la informacion es correcta
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'surname' => $request['surname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => 'Cliente' // Asignar el rol predeterminado
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success'   => true,
            'authToken' => $token,
            'tokenType' => 'Bearer',
            'message'   => 'User registered successfully'
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            // Get user
            $user = User::where('email', $credentials["email"])->firstOrFail();
            // Revoke all old tokens
            $user->tokens()->delete();
            // Generate new token
            $token = $user->createToken("authToken")->plainTextToken;
            // Token response
            return response()->json([
                "success"   => true,
                "authToken" => $token,
                "tokenType" => "Bearer"
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Invalid login credentials"
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        if ($request->user()->tokens()->exists()) {
            $request->user()->tokens()->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User logged out unauthorized'
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {

            // Validate request
            $request->validate([
                'email' => 'requied|email',
                'password' =>  'required',
            ]);

            // Find user by email
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error('Unauthorized', 401);
            }

            // Cek Password
            $user = User::where('email', 'password')->first();
            if (!Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid Password', 401);
            }

            // Generate token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Return response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Login Succes');
        } catch (Exception $e) {
            return ResponseFormatter::error('Authentication Failed', 400);
        }
    }
}

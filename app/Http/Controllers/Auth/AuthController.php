<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());
        $token = $user->createToken('register-api-token')->plainTextToken;

        return response()->json([
            'user' => $user->name,
            'email' => $user->email,
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }
        $token = $user->createToken('login-api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
        ]);
    }

    public function deleteUser(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user || Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'There is no user with these credentials in our database',
            ]);
        }

        $user->tokens()->delete();
        $user->deleteOrFail();
        return response()->json(['message' => 'User deleted successfully!'], 204);
    }
}

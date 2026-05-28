<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\MeResource;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->validated())) {
            return response()->json(['message' => 'Invalid credentials']);
        }
        $request->session()->regenerate();

        return response()->json([
            'user' => Auth::user(),
        ]);
    }

    public function me()
    {
        $user = Auth::user();

        return new MeResource($user);
    }

    public function profile()
    {
        $user = Auth::user();
        $user->postCount = count($user->posts);
        if (count($user->posts) === 0) {
            $user->postCount = 0;

            return new ProfileResource($user);
        }

        return new ProfileResource($user);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out.']);
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

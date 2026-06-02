<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\MeResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->name = $request->validated('name');
        $user->email = $request->validated('email');
        $user->bio = $request->validated('bio');
        /** @var User $user */
        $user->save();

        return new MeResource($user);
    }
}

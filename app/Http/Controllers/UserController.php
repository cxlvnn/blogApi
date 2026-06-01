<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\MeResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->update($request->validated());

        return new MeResource($user);
    }
}

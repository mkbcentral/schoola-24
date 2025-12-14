<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        return response([
            'user' => new UserResource($user),
            'token' => $request->bearerToken(),
        ], 200);
    }
}

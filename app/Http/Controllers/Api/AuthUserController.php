<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthUserController extends Controller
{
    public function __invoke(Request $request)
    {
        $user=Auth::user();
        return  response([
        'user' => new UserResource($user),
        'token' => $request->bearerToken(),
    ], 200);
    }
}

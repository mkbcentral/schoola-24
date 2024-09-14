<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        try {
            auth()->user()->tokens()->delete();
            return response([
                'message' => 'Déconnexion avec succès.',
            ], 200);
        } catch (HttpException $ex) {
            return response([
                'error' => $ex->getMessage(),
            ], 500);
        }
    }
}

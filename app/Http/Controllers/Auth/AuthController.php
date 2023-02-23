<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Response\Auth\AuthResponse;
use App\Http\Response\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function auth(): JsonResponse
    {
        return Response::create()
            ->handle(AuthResponse::class)
            ->json();
    }
}

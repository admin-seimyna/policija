<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Response\Auth\AuthResponse;
use App\Http\Response\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return Response::create()
            ->setRequest($request)
            ->handle(function (Response $response) {
                if (!Auth::attempt($response->request->getData())) {
                    $response->throwFormError([
                        'password' => trans('auth.message.failed')
                    ]);
                }

                return AuthResponse::create()->get();
            })
            ->json();
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();
    }
}

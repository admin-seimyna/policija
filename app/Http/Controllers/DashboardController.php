<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function payload(): JsonResponse
    {
        return Response::create()
            ->handle(function (Response $response) {
                return [
                    'user/list' => User::get(),
                    'shift/list' => Shift::get(),
                ];
            })
            ->json();
    }
}

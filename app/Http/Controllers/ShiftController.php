<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Shift;
use App\Repository\ShiftRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function pagination(Request $request): JsonResponse
    {
        return Response::create()
            ->setRequest($request)
            ->setQuery(Shift::query())
            ->setRepository(ShiftRepository::class, 'pagination')
            ->setCommit('user/pagination')
            ->setPagination()
            ->handle()
            ->json();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Report;
use App\Repository\ReportRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function pagination(Request $request): JsonResponse
    {
        return Response::create()
            ->setQuery(Report::query())
            ->setRequest($request)
            ->setRepository(ReportRepository::class)
            ->setPagination()
            ->handle()
            ->json();
    }
}

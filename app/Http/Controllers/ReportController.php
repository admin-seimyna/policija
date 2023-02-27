<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Http\Filters\ReportFilter;
use App\Http\Requests\Report\CreateRequest;
use App\Http\Requests\Report\UpdateRequest;
use App\Http\Response\Report\CreateResponse;
use App\Http\Response\Response;
use App\Models\Report;
use App\Repository\ReportRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
            ->setFilter(ReportFilter::class)
            ->setRepository(ReportRepository::class, $request->query('r', 'pagination'))
            ->setCommit('report/pagination')
            ->setPagination()
            ->handle()
            ->json();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function statistic(Request $request): JsonResponse
    {
        return Response::create()
            ->setQuery(Report::query())
            ->setFilter(ReportFilter::class)
            ->setRequest($request)
            ->setRepository(ReportRepository::class, 'statistic')
            ->setCommit('report/statistic')
            ->setGetter('first')
            ->handle()
            ->json();
    }

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return Response::create()
            ->setRequest($request)
            ->handle(CreateResponse::class)
            ->json();
    }

    /**
     * @param Report $report
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(Report $report, UpdateRequest $request): JsonResponse
    {
        return Response::create()
            ->permission($report->user_id !== Auth::id() ? 'report.edit' : null)
            ->setRequest($request)
            ->handle(function (Response $response) use ($report) {
                $report->update($response->request->getData());
                return [
                    'report/updateInPagination' => $report->load(['user','shift'])->toArray()
                ];
            })
            ->json();
    }

    /**
     * @param Report $report
     * @return JsonResponse
     */
    public function destroy(Report $report): JsonResponse
    {
        return Response::create()
            ->permission($report->user_id !== Auth::id() ? 'report.delete' : null)
            ->handle(function (Response $response) use ($report) {
                $report->delete();
                return [
                    'report/removeFromPagination' => $report->toArray()
                ];
            })
            ->json();
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        return Excel::download(new ReportsExport, 'ataskaita.xlsx');
    }
}

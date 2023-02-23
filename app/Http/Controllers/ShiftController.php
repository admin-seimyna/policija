<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shift\CreateRequest;
use App\Http\Requests\Shift\UpdateRequest;
use App\Http\Response\Response;
use App\Http\Response\Shift\CreateResponse;
use App\Models\Shift;
use App\Repository\ShiftRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function pagination(Request $request): JsonResponse
    {
        return Response::create()
            ->permission('shift.list')
            ->setRequest($request)
            ->setQuery(Shift::query())
            ->setRepository(ShiftRepository::class, 'pagination')
            ->setCommit('shift/pagination')
            ->setPagination()
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
            ->permission('shift.create')
            ->setRequest($request)
            ->handle(CreateResponse::class)
            ->json();
    }

    /**
     * @param Shift $shift
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(Shift $shift, UpdateRequest $request): JsonResponse
    {
        return Response::create()
            ->permission('shift.edit')
            ->setRequest($request)
            ->handle(function (Response $response) use ($shift) {
                $shift->update($response->request->getData());
                return [
                    'shift/updateInPagination' => $shift->toArray()
                ];
            })
            ->json();
    }

    /**
     * @param Shift $shift
     * @return JsonResponse
     */
    public function destroy(Shift $shift): JsonResponse
    {
        return Response::create()
            ->permission('shift.delete')
                ->handle(function (Response $response) use ($shift) {
                    $shift->delete();
                    return [
                        'shift/removeFromPagination' => $shift->toArray()
                    ];
                })->json();
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Response\Response;
use App\Http\Response\User\CreateResponse;
use App\Models\User;
use App\Models\UserGroup;
use App\Repository\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function pagination(Request $request): JsonResponse
    {
        return Response::create()
            ->permission('user.list')
            ->setRequest($request)
            ->setQuery(User::query())
            ->setRepository(UserRepository::class, 'pagination')
            ->setCommit('user/pagination')
            ->setPagination()
            ->handle()
            ->json();
    }

    /**
     * @return JsonResponse
     */
    public function payload(): JsonResponse
    {
        return Response::create()
            ->permission('user.list')
            ->handle(function (Response $response) {
                return [
                    'user_group/list' => UserGroup::get()
                ];
            })
            ->json();
    }

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function create(CreateRequest $request): JsonResponse
    {
        return Response::create()
            ->permission('user.create')
            ->setRequest($request)
            ->handle(CreateResponse::class)
            ->json();
    }

    /**
     * @param User $user
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(User $user, UpdateRequest $request): JsonResponse
    {
        return Response::create()
            ->permission('user.edit')
            ->setRequest($request)
            ->handle(function(Response $response) use ($user, $request) {
                $user->update($request->getData());
                return [
                    'user/updateInPagination' => $user->load(['userGroup:id,title'])->toArray()
                ];
            })
            ->json();
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        return Response::create()
            ->permission('user.delete')
            ->handle(function(Response $response) use ($user) {
                $user->delete();
                return [
                    'user/removeFromPagination' => $user->toArray()
                ];
            })
            ->json();
    }
}

<?php

namespace App\Http\Controllers;

use App\Enum\PermissionActionEnum;
use App\Http\Requests\UserGroup\CreateRequest;
use App\Http\Requests\UserGroup\UpdateRequest;
use App\Http\Response\Response;
use App\Http\Response\UserGroup\CreateResponse;
use App\Models\UserGroup;
use App\Repository\UserGroupRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserGroupController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function payload(): JsonResponse
    {
        return Response::create()
            ->permission('user-group.list')
            ->handle(function (Response $response) {
                return [
                    'permission/actions' => PermissionActionEnum::sorted(),
                    'permission/list' => config('permission.permissions'),
                ];
            })
            ->json();
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function pagination(Request $request): JsonResponse
    {
        return Response::create()
            ->permission('user-group.list')
            ->setRequest($request)
            ->setQuery(UserGroup::query())
            ->setRepository(UserGroupRepository::class, 'pagination')
            ->setCommit('user_group/pagination')
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
            ->permission('user-group.create')
            ->setRequest($request)
            ->handle(CreateResponse::class)
            ->json();
    }

    /**
     * @param UserGroup $userGroup
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UserGroup $userGroup, UpdateRequest $request): JsonResponse
    {
        return Response::create()
            ->permission('user-group.edit')
            ->setRequest($request)
            ->handle(function(Response $response) use ($userGroup, $request) {
                $userGroup->update($request->getData());
                $request->getPermissions()->each(function ($permission) use ($userGroup) {
                    $userGroup->permissions()
                        ->where('group', $permission['group'])
                        ->where('action', $permission['action'])
                        ->update($permission);
                });
                return [
                    'user_group/updateInPagination' => $userGroup->load(['permissions'])->toArray()
                ];
            })
            ->json();
    }

    /**
     * @param UserGroup $userGroup
     * @return JsonResponse
     */
    public function destroy(UserGroup $userGroup): JsonResponse
    {
        return Response::create()
            ->permission('user-group.delete')
            ->handle(function (Response $response) use ($userGroup) {
                $userGroup->delete();
                return [
                    'user_group/removeFromPagination' => $userGroup->toArray()
                ];
            })->json();
    }
}

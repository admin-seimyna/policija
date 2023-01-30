<?php

namespace App\Http\Response\UserGroup;

use App\Http\Requests\UserGroup\CreateRequest;
use App\Http\Response\Response;
use App\Models\UserGroup;
use Illuminate\Http\Request;

class CreateResponse extends Response
{
    /**
     * @param CreateRequest $request
     */
    public function __construct(CreateRequest $request)
    {
        $this->group = UserGroup::create($request->getData());
        $request->getPermissions()->each(function ($permission) {
            $this->group->permissions()->create($permission);
        });
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return [
            'user_group/prependToPagination' => $this->group->load(['permissions'])
        ];
    }
}

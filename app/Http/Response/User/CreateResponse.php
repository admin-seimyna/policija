<?php

namespace App\Http\Response\User;

use App\Http\Requests\User\CreateRequest;
use App\Http\Response\Response;
use App\Models\User;

class CreateResponse extends Response
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * @param CreateRequest $request
     */
    public function __construct(CreateRequest $request)
    {
        $this->user = User::create($request->getData());
    }

    /**
     * @return User[]
     */
    public function get(): array
    {
        return [
            'user/prependToPagination' => $this->user->load(['userGroup:id,title'])->toArray()
        ];
    }
}

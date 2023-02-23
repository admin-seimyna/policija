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
        $data = $request->getData();
        $this->user = User::create($data);
        if (isset($data['role'])) {
            $this->user->role = $data['role'];
            $this->user->update();
        }
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

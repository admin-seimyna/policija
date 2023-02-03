<?php

namespace App\Http\Response\Shift;

use App\Http\Requests\Shift\CreateRequest;
use App\Http\Response\Response;
use App\Models\Shift;
use Illuminate\Http\Request;

class CreateResponse extends Response
{
    /**
     * @var Shift|void
     */
    protected Shift $shift;

    /**
     * @param CreateRequest $request
     */
    public function __construct(CreateRequest $request)
    {
        $this->shift = Shift::create($request->getData());
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return [
            'shift/prependToPagination' => $this->shift->toArray()
        ];
    }
}

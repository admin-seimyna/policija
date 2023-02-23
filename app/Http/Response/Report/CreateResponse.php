<?php

namespace App\Http\Response\Report;

use App\Http\Requests\Report\CreateRequest;
use App\Http\Response\Response;
use App\Models\Report;
use Illuminate\Http\Request;

class CreateResponse extends Response
{
    /**
     * @var Report
     */
    protected Report $report;

    /**
     * @param CreateRequest $request
     */
    public function __construct(CreateRequest $request)
    {
        $this->report = Report::create($request->getData());
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return [
            'report/prependToPagination' => $this->report->load([
                'user',
                'shift'
            ])->toArray()
        ];
    }
}

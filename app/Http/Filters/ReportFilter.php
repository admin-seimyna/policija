<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ReportFilter extends Filter
{
    /**
     * @var string[]
     */
    protected array $filters = [
        'user_id' => 'required|array',
        'shift_id' => 'required|array',
        'date' => 'required|array'
    ];

    /**
     * @var array|string[]
     */
    protected array $methods = [
        'user_id' => 'in',
        'shift_id' => 'in',
    ];

    protected array $sortable = [
        'date',
        'user',
        'shift',
        'events_count',
        'processed_events_count',
        'an_count',
        'traffic_events_count',
        'unprocessed_events_count',
        'total',
    ];

    /**
     * @param Builder $builder
     * @param array $value
     * @return Builder
     */
    public function filterDate(Builder $builder, array $value): Builder
    {
        return $this->applyDateFilter($builder, 'date', $value);
    }

    /**
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function sortUser(Builder $builder, $value): Builder
    {
        return $builder->join('users', function ($join) {
            $join->on('users.id', 'reports.user_id');
        })->orderBy('users.name', $value);
    }

    /**
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function sortShift(Builder $builder, $value): Builder
    {
        return $builder->join('shifts', function ($join) {
            $join->on('shifts.id', 'reports.shift_id');
        })->orderBy('shifts.title', $value);
    }
}

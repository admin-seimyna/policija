<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

class ReportRepository extends Repository
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public static function pagination(Builder $builder): Builder
    {
        return $builder->with([
                'shift',
                'user'
            ])
            ->orderByDesc('date');
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public static function statistic(Builder $builder): Builder
    {
        return $builder->selectRaw('
            SUM(events_count) as events_count,
            SUM(processed_events_count) as processed_events_count,
            SUM(an_count) as an_count,
            SUM(traffic_events_count) as traffic_events_count,
            SUM(unprocessed_events_count) as unprocessed_events_count,
            SUM(total) as total
        ');
    }
}

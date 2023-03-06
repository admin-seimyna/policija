<?php

namespace App\Observers;

use App\Models\Report;

class ReportObserver
{
    /**
     * @var string[]
     */
    protected $fields = [
        'processed_events_count',
        'unprocessed_events_count',
    ];

    /**
     * @param Report $report
     */
    public function creating(Report $report): void
    {
        $report->total = 0;
        collect($this->fields)->each(static function ($fieldName) use ($report) {
            $report->total += (int)$report->{$fieldName};
        });
    }

    /**
     * @param Report $report
     */
    public function updating(Report $report): void
    {
        $report->total = 0;
        collect($this->fields)->each(static function ($fieldName) use ($report) {
            $report->total += (int)$report->{$fieldName};
        });
    }
}

<?php

namespace App\Exports;

use App\Http\Filters\ReportFilter;
use App\Models\Report;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            trans('field.title.date'),
            trans('field.title.user'),
            trans('field.title.shift'),
            trans('field.title.processed_events_count'),
            trans('field.title.an_count'),
            trans('field.title.traffic_events_count'),
            trans('field.title.unprocessed_events_count'),
            trans('field.title.total'),
            trans('field.title.comment'),
        ];
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        $records = ReportFilter::apply(Report::query())->get();
        $data = $records->map(static function (Report $report) {
            return [
                $report->date,
                $report->user->name,
                $report->shift->title,
                strval($report->processed_events_count),
                strval($report->an_count),
                strval($report->traffic_events_count),
                strval($report->unprocessed_events_count),
                strval($report->total),
                strip_tags($report->comment ?? ''),
            ];
        });

        $count = $records->count();
        $processedEventsCount = $records->sum('processed_events_count');
        $anCount = $records->sum('an_count');
        $trafficEventsCount = $records->sum('traffic_events_count');
        $unprocessedEventsCount = $records->sum('unprocessed_events_count');
        $total = $records->sum('total');

        $data->push([
            null,
            null,
            $count,
            strval($processedEventsCount),
            strval($anCount),
            strval($trafficEventsCount),
            strval($unprocessedEventsCount),
            strval($total),
            null
        ]);

        $data->push([
            null,
            null,
            null,
            strval(number_format(($processedEventsCount / $count) ?? 0, 2, ',', '')),
            strval(number_format(($anCount / $count) ?? 0, 2, ',', '')),
            strval(number_format(($trafficEventsCount / $count) ?? 0, 2, ',', '')),
            strval(number_format(($unprocessedEventsCount / $count) ?? 0, 2, ',', '')),
            strval(number_format(($total / $count) ?? 0, 2, ',', '')),
            null
        ]);

        return $data;
    }
}

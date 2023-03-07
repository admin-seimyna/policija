<?php

namespace App\Models;

use App\Models\Scopes\UserReportScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'events_count',
        'processed_events_count',
        'an_count',
        'traffic_events_count',
        'unprocessed_events_count',
        'date',
        'comment',
        'shift_id',
        'user_id'
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserReportScope());

        static::creating(static function (Report $report) {
            $report->total = 0;
            collect([
                'processed_events_count',
                'unprocessed_events_count',
            ])->each(static function ($fieldName) use ($report) {
                $report->total += (int)$report->{$fieldName} ?? 0;
            });
        });

        static::updating(static function (Report $report) {
            $report->total = 0;
            collect([
                'processed_events_count',
                'unprocessed_events_count',
            ])->each(static function ($fieldName) use ($report) {
                $report->total += (int)$report->{$fieldName} ?? 0;
            });
        });
    }

    /**
     * @return BelongsTo
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

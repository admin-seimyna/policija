<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'time_from',
        'time_to',
    ];

    protected $casts = [
        'time_from' => 'date:H:s'
    ];

    /**
     * @param string|null $value
     * @return string|null
     */
    public function getTimeFromAttribute(?string $value): ?string
    {
        if (!$value) return null;
        return Carbon::now()->setTime(...explode(':', $value))->format('H:i');
    }

    /**
     * @param string|null $value
     * @return string|null
     */
    public function getTimeToAttribute(?string $value): ?string
    {
        if (!$value) return null;
        return Carbon::now()->setTime(...explode(':', $value))->format('H:i');
    }
}

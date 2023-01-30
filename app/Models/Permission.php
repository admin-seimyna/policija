<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'group',
        'action',
        'value'
    ];
}

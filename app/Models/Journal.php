<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journal extends Model
{
    protected $table = 'journals';

    protected $fillable = [
        'month',
        'year',
        'description',
        'date_journaling',
        'content',
        'debt_account',
        'has_account',
        'amount',
    ];
}

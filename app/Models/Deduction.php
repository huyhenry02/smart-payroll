<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deduction extends Model
{
    protected $table = 'deductions';

    protected $fillable = [
        'name',
        'rate',
    ];
}

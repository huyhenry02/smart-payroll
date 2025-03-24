<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Allowance extends Model
{
    protected $table = 'allowances';

    protected $fillable = [
        'name',
        'employee_id',
        'amount',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

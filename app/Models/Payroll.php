<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'base_salary',
        'total_allowance',
        'total_deduction',
        'net_salary',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

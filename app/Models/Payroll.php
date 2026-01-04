<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $table = 'payrolls';
    protected $fillable = [
        'employee_id',
        'created_by',
        'month',
        'year',
        'salary_v1',
        'salary_gross',
        'total_allowance',
        'total_deduction',
        'total_bonus',
        'working_shift_amount',
        'tax_amount',
        'tax_rate',
        'net_salary_before_tax',
        'net_salary_after_tax',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $table = 'payrolls';
    public const BASE_SALARY = 3860000;
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'salary_v1',
        'total_allowance',
        'total_deduction',
        'total_bonus',
        'working_shift_amount',
        'tax_amount',
        'net_salary_before_tax',
        'net_salary_after_tax',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

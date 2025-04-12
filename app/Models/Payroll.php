<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $table = 'payrolls';
    public const TAX_SELF = 11000000;
    public const TAX_DEPENDENT = 4400000;
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'salary_v1',
        'unit_price_v1',
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

    public static function getTaxBrackets(): array
    {
        return [
            ['limit' => 5000000,  'rate' => 0.05],
            ['limit' => 10000000, 'rate' => 0.10],
            ['limit' => 18000000, 'rate' => 0.15],
            ['limit' => 32000000, 'rate' => 0.20],
            ['limit' => 52000000, 'rate' => 0.25],
            ['limit' => 80000000, 'rate' => 0.30],
            ['limit' => PHP_INT_MAX, 'rate' => 0.35],
        ];
    }
}

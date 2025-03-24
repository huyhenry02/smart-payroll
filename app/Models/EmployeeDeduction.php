<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDeduction extends Model
{
    protected $table = 'employee_deductions';

    protected $fillable = [
        'employee_id',
        'deduction_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'employee_code',
        'full_name',
        'dob',
        'gender',
        'address',
        'department_id',
        'position_id',
        'base_salary',
        'salary_factor',
        'seniority',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}

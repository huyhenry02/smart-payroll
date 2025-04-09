<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'avatar',
        'employee_code',
        'full_name',
        'dob',
        'gender',
        'identity_number',
        'identity_issued_date',
        'identity_issued_place',
        'address',
        'phone',
        'department_id',
        'position_id',
        'start_date',
        'employment_status',
        'contract_type',
        'salary_factor',
        'seniority',
        'tax_code',
        'bank_account',
        'bank_name',
        'education_level',
        'specialization',
        'number_of_dependent'
    ];
    public const STATUS_WORKING = 'working';
    public const STATUS_RESIGNED = 'resigned';
    public const STATUS_ON_LEAVE = 'on_leave';
    public const STATUS_LIST = [
        self::STATUS_WORKING => 'Đang làm việc',
        self::STATUS_RESIGNED => 'Đã nghỉ việc',
    ];
    public const GENDER_MALE = 'male';
    public const GENDER_FEMALE = 'female';
    public const GENDER_OTHER = 'other';
    public const GENDERS = [
        self::GENDER_MALE => 'Nam',
        self::GENDER_FEMALE => 'Nữ',
        self::GENDER_OTHER => 'Khác',
    ];
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function allowances(): BelongsToMany
    {
        return $this->belongsToMany(Allowance::class, 'employee_allowances');
    }

    public function deductions(): BelongsToMany
    {
        return $this->belongsToMany(Deduction::class, 'employee_deductions');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}

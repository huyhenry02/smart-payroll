<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';
    public const GENERAL_CATALOG = 'general_catalog';
    public const SYSTEM = 'system';
    public const ALLOWANCE_DEDUCTION = 'allowance_deduction';
    public const ATTENDANCE = 'attendance';
    public const ACCOUNTING = 'accounting';
    public const JOURNAL = 'journal';
    public const TITLES = [
        self::SYSTEM => 'Hệ thống',
        self::GENERAL_CATALOG => 'Danh mục chung',
        self::ALLOWANCE_DEDUCTION => 'Phụ cấp & Trích nộp',
        self::ATTENDANCE => 'Chấm công',
        self::ACCOUNTING => 'Kế toán & Tiền lương',
        self::JOURNAL => 'Báo cáo & Sổ kế toán',
    ];
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'created_by',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Allowance extends Model
{
    protected $table = 'allowances';

    protected $fillable = [
        'name',
        'rate',
        'type',
    ];
    public const TYPE_POSITION = 'position';
    public const TYPE_REGION = 'region';
    public const TYPE_HAZARD = 'hazard';
    public const TYPE_RESPONSIBILITY = 'responsibility';
    public const TYPES = [
        self::TYPE_POSITION => 'Phụ cấp chức vụ',
        self::TYPE_HAZARD => 'Phụ cấp độc hại',
        self::TYPE_RESPONSIBILITY => 'Phụ cấp trách nhiệm',
        self::TYPE_REGION => 'Phụ cấp vùng',
    ];

    public const TYPES_REALITY_CODE = [
        self::TYPE_POSITION => 'PCCV',
        self::TYPE_HAZARD => 'PCĐH',
        self::TYPE_RESPONSIBILITY => 'PCTN',
    ];

    public const TYPES_REALITY_TEXT = [
        self::TYPE_POSITION => 'Phụ cấp CV',
        self::TYPE_HAZARD => 'Phụ cấp ĐH',
        self::TYPE_RESPONSIBILITY => 'Phụ cấp TN',
    ];
}

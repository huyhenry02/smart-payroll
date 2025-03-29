<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingShift extends Model
{
    protected $table = 'working_shifts';

    protected $fillable = [
        'type',
        'hourly_rate',
    ];

    public const TYPE_MORNING = 'morning';
    public const TYPE_AFTERNOON = 'afternoon';
    public const TYPE_NIGHT = 'night';
    public const TYPE_WEEKEND = 'weekend';
    public const TYPE_HOLIDAY = 'holiday';

    public const TYPES = [
        self::TYPE_MORNING => 'Buổi sáng',
        self::TYPE_AFTERNOON => 'Buổi chiều',
        self::TYPE_NIGHT => 'Buổi tối',
        self::TYPE_WEEKEND => 'Cuối tuần',
        self::TYPE_HOLIDAY => 'Ngày lễ',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'code',
        'module_code',
        'name',
        'description',
        'is_active',
    ];
}

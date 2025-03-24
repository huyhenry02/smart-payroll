<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingAccount extends Model
{
    protected $table = 'accounting_accounts';

    protected $fillable = [
        'account_code',
        'account_name',
        'account_level',
    ];
}

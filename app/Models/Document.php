<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'uploaded_by',
        'file_type',
        'book_type',
        'file_name',
        'file_path',
    ];
}

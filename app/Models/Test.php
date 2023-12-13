<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'attachment',
        'attachment_names'
    ];
    protected $casts = [
        'attachment' => 'array',
        'attachment_names' => 'array'
    ];
}

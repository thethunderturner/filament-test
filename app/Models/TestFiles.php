<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestFiles extends Model
{
    use HasFactory;

    protected $table = 'test_files';

    protected $fillable = [
        'file_name',
        'file_uuid',
        'model_name',
    ];
}

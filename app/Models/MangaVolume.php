<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MangaVolume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'volume',
        'dir_path',
        'image_path',
        'description'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'package_id',
        'volume_id',
        'dir_path',
        'title',
        'story',
        'pages'
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}

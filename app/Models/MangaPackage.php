<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MangaPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'dir_path',
        'package_img_path'
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}

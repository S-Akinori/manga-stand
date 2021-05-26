<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'story_id',
        'content',
    ];

    public function mangaPackage() {
        return $this->belongsTo(MangaPackage::class);
    }

    public function manga() {
        return $this->belongsTo(Manga::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

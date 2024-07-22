<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'category_id',
    ];


    /**
     *  post belongs to one user
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *  post belongs to one category
    */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    //
    use HasFactory;
    protected $table = 'posts';
    protected $fillable = ['image', 'title', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * image
     *@return Attribute
     */

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/posts/' . $image),
        );
    }
}

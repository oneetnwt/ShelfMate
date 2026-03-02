<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bio'];

    /** An author can have written multiple books. */
    public function books()
    {
        return $this->belongsToMany(Book::class)
            ->withTimestamps();
    }
}
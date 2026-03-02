<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'isbn',
        'genre',
        'published_year',
        'total_copies',
        'available_copies',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    /** A book can have multiple authors (pivot: author_book). */
    public function authors()
    {
        return $this->belongsToMany(Author::class)->withTimestamps();
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    public function activeBorrows()
    {
        return $this->hasMany(BorrowRecord::class)->where('status', 'active');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Comma-separated author names, e.g. "Frank Herbert, Brian Herbert". */
    public function authorNames(): string
    {
        return $this->authors->pluck('name')->join(', ') ?: 'Unknown';
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->available_copies > 0;
    }
}
<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    use HasFactory;

    /** Fine rate in Philippine Peso per overdue day, per book. */
    const FINE_PER_DAY = 10;

    protected $fillable = [
        'book_id',
        'borrower_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'fine_amount',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_amount' => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function borrower()
    {
        return $this->belongsTo(Borrower::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
            ->where('due_date', '<', today());
    }

    // ── Business Logic ────────────────────────────────────────────────────────

    /**
     * Whether the book is currently overdue (not yet returned, past due date).
     */
    public function isOverdue(): bool
    {
        return $this->status === 'active' && today()->isAfter($this->due_date);
    }

    /**
     * How many days overdue this record is (0 if not overdue).
     */
    public function daysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return (int) today()->startOfDay()->diffInDays($this->due_date->startOfDay());
    }

    /**
     * Compute the fine amount for this record.
     *
     * Formula: ₱10 × days_overdue × 1 book (per record).
     * Sum multiple records together to get the total fine for a partial/full return.
     *
     * Returns the stored fine_amount for already-returned records.
     */
    public function computeFine(): float
    {
        if ($this->status === 'returned') {
            return (float) $this->fine_amount;
        }

        return $this->daysOverdue() * self::FINE_PER_DAY;
    }
}
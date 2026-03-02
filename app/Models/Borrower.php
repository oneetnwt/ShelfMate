<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact_number', 'email'];

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    public function activeBorrows()
    {
        return $this->hasMany(BorrowRecord::class)->where('status', 'active');
    }

    public function returnedBorrows()
    {
        return $this->hasMany(BorrowRecord::class)->where('status', 'returned');
    }
}
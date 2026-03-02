<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $borrowers = Borrower::withCount([
            'borrowRecords as total_borrows',
            'borrowRecords as active_borrows' => fn($query) => $query->where('status', 'active'),
            'borrowRecords as overdue_borrows' => fn($query) => $query->where('status', 'active')->where('due_date', '<', today()),
        ])
            ->withSum(['borrowRecords as fines_collected' => fn($q) => $q->where('status', 'returned')->where('fine_amount', '>', 0)], 'fine_amount')
            ->when($q, fn($query) => $query->where(function ($inner) use ($q) {
                $inner->where('name', 'like', "%{$q}%")
                    ->orWhere('contact_number', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.borrowers.index', compact('borrowers', 'q'));
    }

    public function show(Borrower $borrower)
    {
        $borrower->loadCount([
            'borrowRecords as total_borrows',
            'borrowRecords as active_borrows' => fn($q) => $q->where('status', 'active'),
            'borrowRecords as overdue_borrows' => fn($q) => $q->where('status', 'active')->where('due_date', '<', today()),
        ]);

        $finesCollected = $borrower->borrowRecords()
            ->where('status', 'returned')
            ->where('fine_amount', '>', 0)
            ->sum('fine_amount');

        $records = $borrower->borrowRecords()
            ->with('book')
            ->orderByDesc('borrow_date')
            ->get();

        return view('admin.borrowers.show', compact('borrower', 'records', 'finesCollected'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::withCount('books');

        if ($search = $request->query('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $authors = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:5000'],
        ]);

        $author = Author::create($data);

        return redirect()
            ->route('admin.authors.index')
            ->with('success', "Author '{$author->name}' was created successfully.");
    }

    public function show(Author $author)
    {
        $author->loadCount('books');
        $author->load(['books' => fn($q) => $q->orderBy('title')]);

        return view('admin.authors.show', compact('author'));
    }

    public function edit(Author $author)
    {
        $author->load(['books' => fn($q) => $q->orderBy('title')]);

        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:5000'],
        ]);

        $author->update($data);

        return redirect()
            ->route('admin.authors.index')
            ->with('success', "Author '{$author->name}' was updated successfully.");
    }

    public function destroy(Author $author)
    {
        // Detach all pivot rows before deleting so no orphan records
        $author->books()->detach();
        $name = $author->name;
        $author->delete();

        return redirect()
            ->route('admin.authors.index')
            ->with('success', "Author '{$name}' was deleted.");
    }
}

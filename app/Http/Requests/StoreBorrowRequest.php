<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // public — no auth required
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'book_ids' => ['required', 'array', 'min:1', 'max:10'],
            'book_ids.*' => ['required', 'integer', 'exists:books,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'book_ids.required' => 'Please select at least one book to borrow.',
            'book_ids.min' => 'Please select at least one book to borrow.',
            'book_ids.max' => 'You can borrow at most 10 books at a time.',
            'book_ids.*.exists' => 'One or more selected books do not exist.',
        ];
    }
}

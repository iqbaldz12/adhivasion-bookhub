<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'          => ['required','string','max:255'],
            'author'         => ['required','string','max:255'],
            'published_year' => ['required','digits:4','integer','min:1000','max:'.date('Y')],
            'isbn'           => ['required','string','max:32','unique:books,isbn'],
            'stock'          => ['required','integer','min:0'],
        ];
    }
}

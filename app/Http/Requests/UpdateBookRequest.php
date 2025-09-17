<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'          => ['sometimes','required','string','max:255'],
            'author'         => ['sometimes','required','string','max:255'],
            'published_year' => ['sometimes','required','digits:4','integer','min:1000','max:'.date('Y')],
            'isbn'           => ['sometimes','required','string','max:32', Rule::unique('books', 'isbn')->ignore($this->route('book'))],
            'stock'          => ['sometimes','required','integer','min:0'],
        ];
    }
}

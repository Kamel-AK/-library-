<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ISBN' => ['required', 'string', 'size:13', 'unique:books,ISBN'],
            'title' => ['required', 'string', 'max:70'],
            'price' => ['required', 'numeric', 'between:0,99.99'],
            'mortgage' => ['required', 'numeric', 'between:0,9999.99'],
            'authorship_date' => ['nullable', 'date', 'before_or_equal:today'],
            'category_id' => ['required', 'exists:categories,id'],
            'cover' => ['nullable', 'image', 'max:2048'],
            'author_ids' => ['nullable', 'array'],
            'author_ids.*' => ['exists:authors,id'],
        ];
    }
}

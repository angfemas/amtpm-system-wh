<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreChecklistItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->can('checklist.create');
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('options') && is_string($this->options)) {
            $options = array_filter(array_map('trim', explode(',', $this->options)));
            $this->merge(['options' => $options]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unit_category_ids' => ['required', 'array', 'min:1'],
            'unit_category_ids.*' => ['exists:unit_categories,id'],
            'nama_item' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'tipe' => ['required', 'in:checkbox,text,number,select'],
            'options' => ['nullable', 'array'],
            'urutan' => ['required', 'integer', 'min:0'],
            'is_required' => ['boolean'],
            'is_active' => ['boolean'],
            'sub_items' => ['nullable', 'array'],
            'sub_items.*.judul' => ['required_with:sub_items', 'string', 'max:255'],
            'sub_items.*.deskripsi' => ['nullable', 'string', 'max:500'],
            'sub_items.*.urutan' => ['nullable', 'integer', 'min:0'],
        ];
    }
}

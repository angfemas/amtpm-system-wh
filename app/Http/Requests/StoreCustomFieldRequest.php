<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCustomFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->can('custom_fields.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unit_category_id' => ['required', 'exists:unit_categories,id'],
            'nama_field' => ['required', 'string', 'max:255'],
            'label_field' => ['required', 'string', 'max:255'],
            'tipe_field' => ['required', 'in:text,number,date,select,textarea'],
            'options' => ['nullable', 'array'],
            'placeholder' => ['nullable', 'string', 'max:500'],
            'is_required' => ['boolean'],
            'urutan' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}

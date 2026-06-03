<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreWarehouseAreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user && $user->can('areas.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:warehouse_areas,name', 'min:2'],
            'description' => ['nullable', 'string', 'max:1000', 'min:3'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:999999'],
            'color' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => ['boolean'],
        ];
    }
    
    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama area wajib diisi.',
            'name.min' => 'Nama area minimal 2 karakter.',
            'name.unique' => 'Nama area sudah digunakan.',
            'description.min' => 'Deskripsi minimal 3 karakter.',
            'capacity.min' => 'Kapasitas minimal 1.',
            'capacity.max' => 'Kapasitas maksimal 999999.',
            'color.regex' => 'Format warna tidak valid. Gunakan format hex (#RRGGBB).',
        ];
    }
}

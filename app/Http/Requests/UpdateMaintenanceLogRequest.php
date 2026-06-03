<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMaintenanceLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->can('maintenance.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unit_id' => ['required', 'exists:units,id'],
            'checklist_data' => ['required', 'array'],
            'custom_field_data' => ['nullable', 'array'],
            'kilometer_input' => ['nullable', 'numeric', 'min:0'],
            'hour_meter_input' => ['nullable', 'numeric', 'min:0'],
            'foto_paths' => ['nullable', 'array', 'max:3'],
            'foto_paths.*' => ['image', 'mimes:jpg,jpeg,png', 'max:10240'],
            'existing_foto_paths' => ['nullable', 'array'],
            'existing_foto_paths.*' => ['string'],
            'tag_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
            'catatan_kerusakan' => ['nullable', 'string', 'max:1000'],
            'tag_type' => ['required', 'in:none,red_tag,white_tag'],
            'tag_description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:submitted,approved,completed,overdue'],
        ];
    }
}

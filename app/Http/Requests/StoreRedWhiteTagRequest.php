<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRedWhiteTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->can('tags.create');
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
            'maintenance_log_id' => ['nullable', 'exists:maintenance_logs,id'],
            'tag_type' => ['required', 'in:red_tag,white_tag'],
            'description' => ['required', 'string', 'max:1000'],
            'severity' => ['required', 'in:low,medium,high'],
            'status' => ['required', 'in:open,resolved,closed'],
            'target_resolution_date' => ['nullable', 'date'],
            'actual_resolution_date' => ['nullable', 'date'],
            'resolution_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

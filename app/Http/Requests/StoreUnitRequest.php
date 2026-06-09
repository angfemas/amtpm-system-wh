<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Temporarily disable permission check
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nomor_urut' => ['nullable', 'integer', 'min:1'],
            'kode_unit' => ['required', 'string', 'max:50', 'unique:units,kode_unit'],
            'nama_unit' => ['required', 'string', 'max:255'],
            'unit_category_id' => ['required', 'exists:unit_categories,id'],
            'warehouse_area_id' => ['required', 'exists:warehouse_areas,id'],
            'jenis_maintenance' => ['required', 'in:preventive,corrective,predictive'],
            'tanggal_maintenance_terakhir' => ['nullable', 'date'],
            'interval_hari' => ['required', 'integer', 'min:1'],
            'kilometer' => ['nullable', 'numeric', 'min:0'],
            'hour_meter' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'foto_unit' => ['nullable', 'image', 'max:2048'],
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
            'nomor_urut.integer' => 'Nomor unit harus berupa angka.',
            'nomor_urut.min' => 'Nomor unit minimal 1.',
            'kode_unit.required' => 'Kode unit wajib diisi.',
            'kode_unit.unique' => 'Kode unit sudah digunakan.',
            'nama_unit.required' => 'Nama unit wajib diisi.',
            'unit_category_id.required' => 'Kategori unit wajib dipilih.',
            'unit_category_id.exists' => 'Kategori unit tidak valid.',
            'warehouse_area_id.required' => 'Area gudang wajib dipilih.',
            'warehouse_area_id.exists' => 'Area gudang tidak valid.',
            'jenis_maintenance.required' => 'Jenis maintenance wajib dipilih.',
            'jenis_maintenance.in' => 'Jenis maintenance tidak valid.',
            'interval_hari.required' => 'Interval hari wajib diisi.',
            'interval_hari.min' => 'Interval hari minimal 1.',
        ];
    }
}

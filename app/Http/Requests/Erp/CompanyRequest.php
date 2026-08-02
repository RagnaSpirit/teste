<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('company')?->id ?? $this->route('company');
        return [
            'trade_name' => 'required|string|max:255', 'legal_name' => 'required|string|max:255',
            'cnpj' => ['required', 'string', 'max:18', 'unique:erp_companies,cnpj,' . $id, new ValidCnpjRule()],
            'state_registration' => 'nullable|string|max:50', 'municipal_registration' => 'nullable|string|max:50',
            'phone' => ['required', 'regex:/^\+?[0-9\s().-]{8,20}$/'], 'whatsapp' => ['nullable', 'regex:/^\+?[0-9\s().-]{8,20}$/'],
            'email' => 'required|email|max:255', 'zip_code' => 'required|string|max:9', 'address' => 'required|string|max:255',
            'number' => 'required|string|max:30', 'complement' => 'nullable|string|max:255', 'city' => 'required|string|max:120', 'state' => 'required|string|size:2',
            'logo_path' => 'nullable|string|max:255', 'primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/', 'secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'business_hours' => 'required|string|max:255', 'delivery_fee' => 'required|numeric|min:0', 'daily_goal' => 'required|numeric|min:0', 'monthly_goal' => 'required|numeric|min:0',
        ];
    }
}

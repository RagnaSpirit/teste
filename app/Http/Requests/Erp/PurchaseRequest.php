<?php
namespace App\Http\Requests\Erp;
use Illuminate\Foundation\Http\FormRequest;
class PurchaseRequest extends FormRequest { public function authorize(): bool { return true; } public function rules(): array { return ['company_id'=>'required|exists:erp_companies,id','supplier_id'=>'required|exists:erp_suppliers,id','purchase_date'=>'required|date','invoice_number'=>'nullable|string|max:120','freight'=>'required|numeric|min:0','discount'=>'required|numeric|min:0','taxes'=>'required|numeric|min:0','items'=>'required|array|min:1','items.*.ingredient_id'=>'required|exists:erp_ingredients,id','items.*.quantity'=>'required|numeric|min:0.001','items.*.value'=>'required|numeric|min:0']; } }

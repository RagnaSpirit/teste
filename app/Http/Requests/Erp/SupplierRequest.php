<?php
namespace App\Http\Requests\Erp;
use Illuminate\Foundation\Http\FormRequest;
class SupplierRequest extends FormRequest { public function authorize(): bool { return true; } public function rules(): array { return ['company_id'=>'required|exists:erp_companies,id','name'=>'required|string|max:255','cnpj'=>['required','string','max:18',new ValidCnpjRule()],'contact'=>'required|string|max:255','phone'=>['required','regex:/^\+?[0-9\s().-]{8,20}$/'],'whatsapp'=>['nullable','regex:/^\+?[0-9\s().-]{8,20}$/'],'email'=>'required|email|max:255','address'=>'required|string|max:255','city'=>'required|string|max:120','state'=>'required|string|size:2','category'=>'required|string|max:120','observations'=>'nullable|string','status'=>'required|in:active,inactive']; } }

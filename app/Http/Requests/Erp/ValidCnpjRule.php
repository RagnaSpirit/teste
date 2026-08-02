<?php

namespace App\Http\Requests\Erp;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCnpjRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = preg_replace('/\D/', '', (string) $value);
        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) { $fail('CNPJ inválido.'); return; }
        for ($t = 12; $t < 14; $t++) {
            $d = 0; $p = $t - 7;
            for ($c = 0; $c < $t; $c++) { $d += (int) $cnpj[$c] * $p--; if ($p < 2) { $p = 9; } }
            $d = ((10 * $d) % 11) % 10;
            if ((int) $cnpj[$t] !== $d) { $fail('CNPJ inválido.'); return; }
        }
    }
}

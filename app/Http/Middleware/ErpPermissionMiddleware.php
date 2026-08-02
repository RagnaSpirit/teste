<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ErpPermissionMiddleware
{
    private const ACCESS = [
        'administrador' => ['company', 'suppliers', 'ingredients', 'stock', 'purchases', 'dashboard'],
        'gerente' => ['company', 'suppliers', 'ingredients', 'stock', 'purchases', 'dashboard'],
        'estoque' => ['ingredients', 'stock', 'dashboard'],
        'compras' => ['suppliers', 'ingredients', 'purchases', 'dashboard'],
        'financeiro' => [],
    ];

    public function handle(Request $request, Closure $next, string $area): Response
    {
        $role = strtolower((string) (auth('admin')->user()?->role?->name ?? 'administrador'));
        $allowed = self::ACCESS[$role] ?? self::ACCESS['administrador'];

        abort_unless(in_array($area, $allowed, true), 403, 'Perfil sem permissão para este módulo do ERP.');

        return $next($request);
    }
}

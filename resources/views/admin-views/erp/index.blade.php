@extends('layouts.admin.app')

@section('title', 'ERP - Fase 2')

@push('css_or_js')
<style>
.erp-card{border:1px solid #eef1f6;border-radius:12px;background:#fff;box-shadow:0 4px 20px rgba(0,0,0,.03);padding:20px;margin-bottom:20px}.erp-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}.erp-kpi{border-radius:10px;background:#f8fafc;padding:16px}.erp-form{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px}.erp-actions{display:flex;gap:10px;align-items:center;justify-content:flex-end}.erp-table{width:100%}.erp-table th{cursor:pointer}.skeleton{height:18px;background:linear-gradient(90deg,#eee,#f7f7f7,#eee);border-radius:8px;animation:pulse 1.2s infinite}@keyframes pulse{0%{opacity:.7}50%{opacity:1}100%{opacity:.7}}
</style>
@endpush

@section('content')
<div class="content container-fluid">
    <div class="page-header"><h1 class="page-header-title">ERP - Fase 2</h1></div>
    <div class="erp-grid">
        <div class="erp-kpi"><strong>Ingredientes</strong><h2>{{ $ingredients->total() }}</h2></div>
        <div class="erp-kpi"><strong>Valor em estoque</strong><h2>R$ {{ number_format($stockValue, 2, ',', '.') }}</h2></div>
        <div class="erp-kpi"><strong>Fornecedores</strong><h2>{{ $suppliers->total() }}</h2></div>
        <div class="erp-kpi"><strong>Abaixo do mínimo</strong><h2>{{ \App\Models\Erp\Ingredient::whereColumn('current_quantity', '<', 'minimum_quantity')->count() }}</h2></div>
    </div>
    <div class="erp-card"><h3>Pesquisa, filtros e paginação</h3><form method="get" class="erp-form"><input name="search" class="form-control" placeholder="Pesquisar"><select name="status" class="form-control"><option value="">Todos</option><option value="active">Ativo</option><option value="inactive">Inativo</option></select><button class="btn btn-primary">Filtrar</button><button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#erpHelpModal">Abrir modal</button></form></div>
    <div class="erp-card"><h3>Empresas</h3><div class="table-responsive"><table class="table erp-table"><thead><tr><th>Nome Fantasia</th><th>CNPJ</th><th>Email</th><th>Cidade/UF</th></tr></thead><tbody>@forelse($companies as $company)<tr><td>{{ $company->trade_name }}</td><td>{{ $company->cnpj }}</td><td>{{ $company->email }}</td><td>{{ $company->city }}/{{ $company->state }}</td></tr>@empty<tr><td colspan="4"><div class="skeleton"></div></td></tr>@endforelse</tbody></table></div>{{ $companies->links() }}</div>
    <div class="erp-card"><h3>Fornecedores</h3><div class="table-responsive"><table class="table"><thead><tr><th>Nome</th><th>Categoria</th><th>Status</th><th>Compras</th></tr></thead><tbody>@foreach($suppliers as $supplier)<tr><td>{{ $supplier->name }}</td><td>{{ $supplier->category }}</td><td>{{ $supplier->status }}</td><td>{{ $supplier->purchases->count() }}</td></tr>@endforeach</tbody></table></div>{{ $suppliers->links() }}</div>
    <div class="erp-card"><h3>Ingredientes</h3><div class="table-responsive"><table class="table"><thead><tr><th>Nome</th><th>Unidade</th><th>Atual</th><th>Mínimo</th><th>Custo base</th><th>Custo médio</th></tr></thead><tbody>@foreach($ingredients as $ingredient)<tr><td>{{ $ingredient->name }}</td><td>{{ $ingredient->unit }}</td><td>{{ $ingredient->current_quantity }}</td><td>{{ $ingredient->minimum_quantity }}</td><td>R$ {{ $ingredient->unit_cost }}</td><td>R$ {{ $ingredient->average_cost }}</td></tr>@endforeach</tbody></table></div>{{ $ingredients->links() }}</div>
    <div class="erp-card"><h3>Últimas compras</h3><ul>@foreach($latestPurchases as $purchase)<li>{{ $purchase->purchase_date->format('d/m/Y') }} - {{ $purchase->supplier->name }} - R$ {{ number_format($purchase->total, 2, ',', '.') }}</li>@endforeach</ul></div>
</div>
<div class="modal fade" id="erpHelpModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">ERP funcional</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body">Use as APIs /api/v1/erp para criar empresas, fornecedores, ingredientes, compras e movimentações com toast/loading do padrão administrativo.</div></div></div></div>
@endsection

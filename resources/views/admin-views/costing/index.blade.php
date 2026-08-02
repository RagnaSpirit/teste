@extends('layouts.admin.app')
@section('title','Ficha técnica, CMV e precificação')
@section('content')
<div class="content container-fluid">
    <div class="page-header"><h1 class="page-header-title">Ficha técnica, CMV e precificação</h1></div>
    <div class="row g-3 mb-4">
        @foreach(['most_profitable'=>'Produtos mais lucrativos','least_profitable'=>'Produtos menos lucrativos','highest_cmv'=>'Maior CMV','lowest_cmv'=>'Menor CMV'] as $key=>$title)
            <div class="col-md-3"><div class="card h-100"><div class="card-header"><h5>{{ $title }}</h5></div><div class="card-body">
                @forelse($dashboard[$key] as $row)<div class="d-flex justify-content-between"><span>{{ $row['name'] }}</span><strong>{{ data_get($row, $key === 'highest_cmv' || $key === 'lowest_cmv' ? 'cmv_percent' : 'profit', 0) }}</strong></div>@empty <div class="text-muted">Sem dados</div>@endforelse
            </div></div></div>
        @endforeach
    </div>
    <div class="card"><div class="card-header d-flex justify-content-between"><form><input name="search" class="form-control" placeholder="Pesquisar produto" value="{{ request('search') }}"></form><div><a class="btn btn-primary" href="{{ route('admin.costing.pricing') }}">Configuração</a> <a class="btn btn-outline-secondary" href="{{ route('admin.costing.export.csv') }}">CSV</a></div></div>
    <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Produto</th><th>CMV</th><th>Custo</th><th>Balcão</th><th>Delivery</th><th>iFood</th><th>Lucro</th><th></th></tr></thead><tbody>
    @foreach($items as $item) @php($p=$item->costProfile?->calculated_prices ?? []) <tr><td>{{ $item->name }}</td><td>{{ $p['cmv_percent'] ?? 0 }}%</td><td>{{ $p['total_cost'] ?? 0 }}</td><td>{{ $p['counter_price'] ?? $item->price }}</td><td>{{ $p['delivery_price'] ?? 0 }}</td><td>{{ $p['ifood_price'] ?? 0 }}</td><td>{{ $p['profit'] ?? 0 }}</td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.costing.show',$item) }}">Abrir</a></td></tr> @endforeach
    </tbody></table></div><div class="card-footer">{{ $items->links() }}</div></div>
</div>
@endsection

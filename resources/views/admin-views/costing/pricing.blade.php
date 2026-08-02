@extends('layouts.admin.app')
@section('title','Configuração de precificação')
@section('content')
<div class="content container-fluid"><div class="page-header"><h1 class="page-header-title">Configuração de precificação</h1></div><div class="card"><div class="card-body"><form method="post" class="row g-3">@csrf
@foreach(['desired_profit_percent'=>'Lucro desejado %','tax_percent'=>'Impostos %','card_fee_percent'=>'Taxa cartão','pix_fee_percent'=>'Taxa PIX','ifood_fee_percent'=>'Taxa iFood','fox_go_fee_percent'=>'Taxa Fox Go','delivery_fee_percent'=>'Taxa Delivery','marketplace_commission_percent'=>'Comissão marketplace','fixed_cost_percent'=>'Custo fixo %','safety_margin_percent'=>'Margem segurança %','packaging_cost'=>'Embalagem'] as $field=>$label)
<div class="col-md-3"><label class="form-label">{{ $label }}</label><input name="{{ $field }}" type="number" step="0.0001" class="form-control" value="{{ old($field, $pricing->{$field} ?? 0) }}" required></div>
@endforeach
<div class="col-12"><button class="btn btn-primary">Salvar e recalcular tudo</button></div></form></div></div></div>
@endsection

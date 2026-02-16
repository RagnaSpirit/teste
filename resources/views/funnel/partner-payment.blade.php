@extends('layouts.landing.app')
@section('title','Como funciona o Pagamento')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="merchant_how_payment" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">Pagamento e Repasse</span>
        <h1>Entenda como seu restaurante recebe</h1>
        <ul class="step-list mt-3">
            <li>Os pedidos e valores ficam registrados no histórico de vendas.</li>
            <li>Taxas e descontos aparecem com transparência no painel.</li>
            <li>O repasse é enviado para a conta cadastrada no ciclo previsto.</li>
        </ul>
        <a class="btn cta-main" href="{{ route('restaurant.create') }}" data-track-event="cta_click" data-track-payload='{"cta":"register_from_how_it_works"}'>Cadastrar agora</a>
    </section>
</div>
@endsection

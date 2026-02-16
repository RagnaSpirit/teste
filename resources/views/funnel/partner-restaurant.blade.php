@extends('layouts.landing.app')
@section('title','Cadastre seu Restaurante | Parceiros')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="merchant_restaurant" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">Parceiros</span>
        <h1>Leve seu restaurante para milhões de clientes no Brasil</h1>
        <p class="lead">Aumente vendas com delivery e retirada, gestão simples e visibilidade nacional com foco local.</p>

        <ul class="step-list mt-3">
            <li>Crie sua conta e cadastre CNPJ, dados do titular e contato.</li>
            <li>Escolha um plano, revise taxas e assine o contrato.</li>
            <li>Configure cardápio, horário e inicie suas vendas.</li>
        </ul>

        <a class="btn cta-main" href="{{ route('restaurant.create') }}" data-track-event="lead_open" data-track-payload='{"lead_type":"merchant"}'>Cadastrar restaurante</a>
    </section>

    <section class="section-box">
        <h3>O que você ganha</h3>
        <div class="grid-cards">
            <div class="card-lite"><strong>Mais pedidos</strong><p class="mb-0">Alcance novos clientes da sua região.</p></div>
            <div class="card-lite"><strong>Operação centralizada</strong><p class="mb-0">Pedido, pagamento e histórico no mesmo painel.</p></div>
            <div class="card-lite"><strong>Escala nacional</strong><p class="mb-0">Estrutura pronta para crescimento sustentável.</p></div>
        </div>
    </section>
</div>
@endsection

@extends('layouts.landing.app')
@section('title',$content['title'] ?? 'Cadastre seu Restaurante | Parceiros')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="merchant_restaurant" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">{{ $content['kicker'] ?? 'Parceiros' }}</span>
        <h1>{{ $content['title'] ?? '' }}</h1>
        <p class="lead">{{ $content['subtitle'] ?? '' }}</p>

        <ul class="step-list mt-3">
            @foreach(($content['steps'] ?? []) as $step)
                <li>{{ $step }}</li>
            @endforeach
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

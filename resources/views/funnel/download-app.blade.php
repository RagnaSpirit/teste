@extends('layouts.landing.app')
@section('title','Baixe o App | Entrega rápida no Brasil')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="download_app" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">App do Cliente</span>
        <h1 class="mb-3">Peça em minutos: mercado, farmácia, restaurante e mais</h1>
        <p class="lead mb-4">Instale agora, receba ofertas da sua região e acompanhe seu pedido em tempo real. Experiência pensada para o consumidor brasileiro, de Norte a Sul.</p>

        <div class="d-flex flex-wrap gap-2 mb-3">
            @if(!empty($links['playstore_url_status']))
                <a class="btn cta-main mr-2" target="_blank" href="{{ $links['playstore_url'] ?? '#' }}"
                   data-track-event="download_click" data-track-payload='{"platform":"android"}'>Baixar no Android</a>
            @endif
            @if(!empty($links['apple_store_url_status']))
                <a class="btn cta-outline" target="_blank" href="{{ $links['apple_store_url'] ?? '#' }}"
                   data-track-event="download_click" data-track-payload='{"platform":"ios"}'>Baixar no iPhone</a>
            @endif
        </div>

        <small class="text-muted">Sem fidelidade • pagamento online e na entrega • suporte local</small>
    </section>

    <section class="section-box mt-4">
        <h3 class="mb-2">Por que instalar agora?</h3>
        <div class="grid-cards">
            <div class="card-lite"><strong>Entrega rápida</strong><p class="mb-0">Parceiros perto de você para reduzir tempo de espera.</p></div>
            <div class="card-lite"><strong>Preço competitivo</strong><p class="mb-0">Cupons, combos e campanhas para economizar todo dia.</p></div>
            <div class="card-lite"><strong>Rastreamento ao vivo</strong><p class="mb-0">Acompanhe o pedido do preparo até a porta da sua casa.</p></div>
            <div class="card-lite"><strong>Atendimento humano</strong><p class="mb-0">Ajuda rápida para resolver qualquer problema de pedido.</p></div>
        </div>
    </section>
</div>
@endsection

@extends('layouts.landing.app')
@section('title',$content['title'] ?? 'Portal do Entregador | Ganhe com liberdade')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="deliveryman_portal_home" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">{{ $content['kicker'] ?? 'Entregador' }}</span>
        <h1>{{ $content['title'] ?? '' }}</h1>
        <p class="lead">{{ $content['subtitle'] ?? '' }}</p>

        <div class="d-flex flex-wrap gap-2">
            <a class="btn cta-main mr-2" href="{{ route('funnel.delivery.start') }}" data-track-event="cta_click" data-track-payload='{"cta":"start_delivery"}'>Começar a entregar</a>
            <a class="btn cta-outline mr-2" href="{{ route('funnel.delivery.help-register') }}" data-track-event="cta_click" data-track-payload='{"cta":"help_register"}'>Ajuda de cadastro</a>
            <a class="btn btn-light" href="{{ route('funnel.delivery.need-help') }}" data-track-event="cta_click" data-track-payload='{"cta":"support"}'>Precisa de ajuda</a>
        </div>
    </section>

    <section class="section-box">
        <h3>Vantagens</h3>
        <div class="grid-cards">
            <div class="card-lite"><strong>Flexibilidade real</strong><p class="mb-0">Você escolhe quando e onde rodar.</p></div>
            <div class="card-lite"><strong>Pagamentos transparentes</strong><p class="mb-0">Visualização de ganhos e repasses com clareza.</p></div>
            <div class="card-lite"><strong>Suporte durante a operação</strong><p class="mb-0">Canal dedicado para emergências e dúvidas.</p></div>
        </div>
    </section>
</div>
@endsection

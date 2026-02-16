@extends('layouts.landing.app')
@section('title','Central de Ajuda | Restaurante')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="merchant_help" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">Ajuda para Parceiros</span>
        <h1>Resolva dúvidas operacionais em poucos minutos</h1>
        <p class="lead">Conteúdo focado em operação real: ferramentas, entregas, pagamentos e crescimento.</p>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn cta-main mr-2" href="{{ route('restaurant.create') }}" data-track-event="cta_click" data-track-payload='{"cta":"register_restaurant"}'>Cadastrar restaurante</a>
            <a class="btn cta-outline" href="{{ route('funnel.partner.plans') }}" data-track-event="section_view" data-track-payload='{"section":"tools"}'>Ver planos</a>
        </div>
    </section>
</div>
@endsection

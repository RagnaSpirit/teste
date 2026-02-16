@extends('layouts.landing.app')
@section('title','Planos e Taxas | Parceiros')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="merchant_plans" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">Planos</span>
        <h1>Escolha o plano ideal para o momento do seu restaurante</h1>
        <div class="grid-cards mt-3">
            <div class="card-lite" data-track-event="plan_view" data-track-payload='{"plan_id":"starter"}'><strong>Starter</strong><p class="mb-0">Para testar operação e ganhar tração local.</p></div>
            <div class="card-lite" data-track-event="plan_view" data-track-payload='{"plan_id":"growth"}'><strong>Growth</strong><p class="mb-0">Para quem já opera e quer escalar vendas.</p></div>
            <div class="card-lite" data-track-event="plan_view" data-track-payload='{"plan_id":"pro"}'><strong>Pro</strong><p class="mb-0">Para alta demanda com suporte prioritário.</p></div>
        </div>
        <a class="btn cta-main mt-3" href="{{ route('restaurant.create') }}" data-track-event="cta_click" data-track-payload='{"cta":"start_registration_from_plans"}'>Começar cadastro</a>
    </section>
</div>
@endsection

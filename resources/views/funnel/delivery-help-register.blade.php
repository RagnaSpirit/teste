@extends('layouts.landing.app')
@section('title','Ajuda de Cadastro do Entregador')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="deliveryman_help_register" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">Suporte de Cadastro</span>
        <h1>Travou no cadastro? Resolve rápido</h1>
        <ul class="step-list mt-3">
            <li>Entre no app e toque em <strong>Falar com o suporte</strong>.</li>
            <li>Envie documento legível (frente/verso) conforme modalidade.</li>
            <li>Aguarde resposta no próprio app e conclua a atualização.</li>
        </ul>
        <a class="btn cta-main" href="{{ route('funnel.delivery.need-help') }}" data-track-event="cta_click" data-track-payload='{"cta":"contact_support"}'>Falar com suporte</a>
    </section>
</div>
@endsection

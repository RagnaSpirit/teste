@extends('layouts.landing.app')
@section('title','Começar a Entregar')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="deliveryman_start" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">Cadastro de Entregador</span>
        <h1>Comece em poucos passos e aumente sua renda</h1>
        <ul class="step-list mt-3">
            <li>Baixe o app do entregador e crie sua conta.</li>
            <li>Preencha seus dados e envie os documentos.</li>
            <li>Após aprovação, ative e comece suas entregas.</li>
        </ul>
        <a class="btn cta-main" href="{{ route('deliveryman.create') }}" data-track-event="download_click" data-track-payload='{"app":"deliveryman"}'>Quero me cadastrar</a>
    </section>

    <section class="section-box">
        <h3>Tempo de aprovação</h3>
        <p class="mb-0">A liberação pode acontecer em poucas horas, variando por cidade e análise de documentos.</p>
    </section>
</div>
@endsection

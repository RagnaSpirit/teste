@extends('layouts.landing.app')
@section('title','Precisa de Ajuda?')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="deliveryman_need_help" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">Central de Segurança</span>
        <h1>Suporte durante a rota e no cadastro</h1>
        <p class="lead">Use o canal de suporte do app para problemas de conta, documentos, corrida e segurança.</p>
        <div class="grid-cards mt-3">
            <div class="card-lite"><strong>Conta e Cadastro</strong><p class="mb-0">Correção de dados e status de aprovação.</p></div>
            <div class="card-lite"><strong>Entrega em andamento</strong><p class="mb-0">Ajuda com ocorrência em rota.</p></div>
            <div class="card-lite"><strong>Segurança/SOS</strong><p class="mb-0">Atendimento prioritário para incidentes.</p></div>
        </div>
    </section>
</div>
@endsection

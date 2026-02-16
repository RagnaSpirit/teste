@extends('layouts.landing.app')
@section('title','Versão Leve (PWA)')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="pwa" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">Versão Leve</span>
        <h1>Instale rápido no celular, sem loja de apps</h1>
        <p class="lead">Ideal para quem quer desempenho, menor consumo de dados e acesso direto na tela inicial. Funciona super bem em Android com Chrome.</p>

        <ul class="step-list mt-3">
            <li>Abra a plataforma no navegador do celular.</li>
            <li>Toque no menu (⋮) e escolha <strong>Adicionar à tela inicial</strong>.</li>
            <li>Confirme e use como se fosse um app.</li>
        </ul>

        <a href="{{ route('home') }}" class="btn cta-main mt-2" data-track-event="cta_click" data-track-payload='{"cta":"open_ifood"}'>Abrir plataforma agora</a>
    </section>

    <section class="section-box">
        <h3 class="mb-2">Benefícios para o público brasileiro</h3>
        <div class="grid-cards">
            <div class="card-lite"><strong>Leve no 4G/5G</strong><p class="mb-0">Carregamento mais rápido em redes móveis.</p></div>
            <div class="card-lite"><strong>Menos memória</strong><p class="mb-0">Boa opção para aparelhos com pouco espaço.</p></div>
            <div class="card-lite"><strong>Acesso instantâneo</strong><p class="mb-0">Atalho direto na tela sem passos extras.</p></div>
        </div>
    </section>
</div>
@endsection

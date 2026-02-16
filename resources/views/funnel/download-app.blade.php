@extends('layouts.landing.app')
@section('title',($content['title'] ?? 'Baixe o App').' | Entrega rápida no Brasil')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="download_app" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">{{ $content['kicker'] ?? 'App do Cliente' }}</span>
        <h1 class="mb-3">{{ $content['title'] ?? '' }}</h1>
        <p class="lead mb-4">{{ $content['subtitle'] ?? '' }}</p>

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

        <small class="text-muted">{{ implode(' • ', $content['highlights'] ?? []) }}</small>
    </section>

    <section class="section-box mt-4">
        <h3 class="mb-2">Por que instalar agora?</h3>
        <div class="grid-cards">
            @foreach(($content['benefits'] ?? []) as $benefit)
                <div class="card-lite"><strong>{{ $benefit['title'] ?? '' }}</strong><p class="mb-0">{{ $benefit['text'] ?? '' }}</p></div>
            @endforeach
        </div>
    </section>
</div>
@endsection

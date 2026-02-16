@extends('layouts.landing.app')
@section('title',$content['title'] ?? 'Versão Leve (PWA)')
@push('css_or_js')
<link rel="manifest" href="{{ route('funnel.pwa.manifest') }}">
<meta name="theme-color" content="#ea1d2c">
<script>
    window.deferredPwaPrompt = null;
    window.addEventListener('beforeinstallprompt', function (event) {
        event.preventDefault();
        window.deferredPwaPrompt = event;
    });

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('{{ route('funnel.pwa.sw') }}').catch(function () {});
        });
    }

    function triggerPwaInstall() {
        if (!window.deferredPwaPrompt) return;
        window.deferredPwaPrompt.prompt();
        window.deferredPwaPrompt.userChoice.finally(function () {
            window.deferredPwaPrompt = null;
        });
    }
</script>
@endpush
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="pwa" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">{{ $content['kicker'] ?? 'Versão Leve' }}</span>
        <h1>{{ $content['title'] ?? '' }}</h1>
        <p class="lead">{{ $content['subtitle'] ?? '' }}</p>

        <ul class="step-list mt-3">
            @foreach(($content['steps'] ?? []) as $step)
                <li>{{ $step }}</li>
            @endforeach
        </ul>

        <button type="button" onclick="triggerPwaInstall()" class="btn cta-outline mt-2 mr-2" data-track-event="install_pwa_click" data-track-payload='{}'>Instalar PWA</button>
        <a href="{{ route('home') }}" class="btn cta-main mt-2" data-track-event="cta_click" data-track-payload='{"cta":"open_ifood"}'>Abrir plataforma agora</a>
    </section>
</div>
@endsection

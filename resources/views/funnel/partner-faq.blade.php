@extends('layouts.landing.app')
@section('title','Perguntas Frequentes | Parceiros')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="merchant_faq" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">FAQ</span>
        <h1>Dúvidas mais comuns antes de começar</h1>
        <p class="lead">Documentos, taxas, prazo de ativação e operação inicial.</p>

        @forelse($faqs as $faq)
            <details class="mb-2" data-track-event="faq_expand" data-track-payload='{"question_id":"{{ $faq->id }}"}'>
                <summary>{{ $faq->question }}</summary>
                <p class="mt-2 mb-0">{{ $faq->answer }}</p>
            </details>
        @empty
            <div class="section-box">
                <p class="mb-0">Ainda não há FAQ cadastrada. Use a central do admin para publicar perguntas frequentes.</p>
            </div>
        @endforelse
    </section>
</div>
@endsection

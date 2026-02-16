@extends('layouts.landing.app')
@section('title',$content['title'] ?? 'Planos e Taxas | Parceiros')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="merchant_plans" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">{{ $content['kicker'] ?? 'Planos' }}</span>
        <h1>{{ $content['title'] ?? '' }}</h1>
        <div class="grid-cards mt-3">
            @foreach(($content['plans'] ?? []) as $plan)
                <div class="card-lite" data-track-event="plan_view" data-track-payload='{"plan_id":"{{ $plan['id'] ?? '' }}"}'><strong>{{ $plan['title'] ?? '' }}</strong><p class="mb-0">{{ $plan['text'] ?? '' }}</p></div>
            @endforeach
        </div>
        <a class="btn cta-main mt-3" href="{{ route('restaurant.create') }}" data-track-event="cta_click" data-track-payload='{"cta":"start_registration_from_plans"}'>Começar cadastro</a>
    </section>
</div>
@endsection

@extends('layouts.landing.app')
@section('title','Bem-vindo de volta | Parceiros')
@section('content')
@include('funnel._tracking')
@include('funnel._style')
<div data-funnel-page="merchant_welcome_back" class="container py-5 funnel-br">
    <section class="hero">
        <span class="kicker">Reativação</span>
        <h1>Volte a vender com força total</h1>
        <p class="lead">Retome sua operação com suporte de onboarding e volte a aparecer para clientes da sua região.</p>
        <a class="btn cta-main" href="{{ route('restaurant.create') }}" data-track-event="cta_click" data-track-payload='{"cta":"reactivate_account"}'>Reativar conta</a>
    </section>
</div>
@endsection

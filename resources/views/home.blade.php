@extends('layouts.landing.app')

@php($business_name = \App\CentralLogics\Helpers::get_business_settings('business_name'))
@section('title', translate('messages.landing_page') . ' | ' . ($business_name != 'null' ? $business_name : 'Sixam Mart'))

@section('content')
    @php($modules = \App\Models\Module::active()->get())
    @php($features = $landing_data['features'] ?? [])
    @php($zones = $landing_data['available_zone_list'] ?? [])

    <style>
        .ifood-clean-page {background: #f8f8f8; color: #1f2937;}
        .ifood-clean-hero {padding: 72px 0 56px; background: linear-gradient(180deg, #fff 0%, #fff5f5 100%);} 
        .ifood-badge {display: inline-flex; gap: .5rem; padding: .45rem .9rem; border-radius: 999px; background: #ffe8eb; color: #d90429; font-weight: 700; font-size: .85rem;}
        .ifood-title {font-size: clamp(2rem, 4vw, 3.3rem); line-height: 1.1; font-weight: 800; margin: 1rem 0;}
        .ifood-subtitle {font-size: 1.05rem; color: #4b5563; max-width: 680px;}
        .ifood-hero-card {background: #fff; border: 1px solid #f0f0f0; border-radius: 22px; padding: 1.25rem; box-shadow: 0 14px 32px rgba(17, 24, 39, .08);}
        .ifood-hero-list {list-style: none; margin: 1rem 0 0; padding: 0;}
        .ifood-hero-list li {padding: .55rem 0; border-bottom: 1px dashed #ececec; color: #374151;}
        .ifood-hero-list li:last-child {border-bottom: none;}
        .ifood-search-card {background: #fff; border-radius: 16px; border: 1px solid #f0f0f0; padding: 1rem; margin-top: 1.2rem;}
        .ifood-input {border: 1px solid #e5e7eb; border-radius: 12px; padding: .75rem .9rem; width: 100%;}

        .ifood-section {padding: 56px 0;}
        .ifood-section-title {font-size: 1.8rem; font-weight: 800; margin-bottom: .4rem;}
        .ifood-section-sub {color: #6b7280; margin-bottom: 1.5rem;}

        .ifood-card {background: #fff; border: 1px solid #ededed; border-radius: 18px; padding: 1.1rem; height: 100%; transition: all .25s ease;}
        .ifood-card:hover {transform: translateY(-4px); box-shadow: 0 10px 24px rgba(17, 24, 39, .08);}
        .ifood-module-icon {width: 46px; height: 46px; object-fit: contain; margin-bottom: .8rem;}
        .ifood-feature-icon {width: 40px; height: 40px; object-fit: contain; border-radius: 10px;}

        .ifood-pill {display: inline-block; border: 1px solid #f1d0d0; color: #be123c; border-radius: 999px; padding: .35rem .8rem; background: #fff; margin: .25rem;}
        .ifood-cta {background: #111827; color: #fff; border-radius: 22px; padding: 2rem;}
    </style>

    <main class="ifood-clean-page">
        <section class="ifood-clean-hero">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="ifood-badge">Delivery • Painel administrativo conectado</span>
                        <h1 class="ifood-title">{{ $landing_data['fixed_header_title'] }}</h1>
                        <p class="ifood-subtitle">{{ $landing_data['fixed_header_sub_title'] }}</p>

                        <div class="ifood-search-card">
                            <label class="form-label mb-2 fw-semibold">Operação centralizada</label>
                            <input class="ifood-input" readonly value="Banners, módulos, zonas, recursos e depoimentos são gerenciados no painel admin e refletidos nesta página." />
                        </div>

                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a class="cmn--btn" href="{{ route('restaurant.create') }}">{{ translate('messages.vendor_registration') }}</a>
                            <a class="cmn--btn btn--secondary" href="{{ route('deliveryman.create') }}">{{ translate('messages.deliveryman_registration') }}</a>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="ifood-hero-card">
                            <img class="w-100 rounded-3 onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}" src="{{ \App\CentralLogics\Helpers::logoFullUrl() }}" alt="logo">
                            <ul class="ifood-hero-list">
                                <li>Catálogo dinâmico por módulo</li>
                                <li>Cadastro rápido de loja e entregador</li>
                                <li>Gestão no admin sem editar código</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ifood-section">
            <div class="container">
                <h2 class="ifood-section-title">{{ $landing_data['fixed_module_title'] }}</h2>
                <p class="ifood-section-sub">{{ $landing_data['fixed_module_sub_title'] }}</p>

                <div class="row g-3">
                    @forelse ($modules as $module)
                        <div class="col-sm-6 col-lg-4">
                            <div class="ifood-card">
                                <img class="ifood-module-icon onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/100x100/2.png') }}" src="{{ $module['icon_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $module->module_name }}">
                                <h5 class="fw-bold mb-2">{{ translate("messages.{$module->module_name}") }}</h5>
                                <div class="text-muted small">{!! \Illuminate\Support\Str::limit(strip_tags($module->description ?? ''), 120) !!}</div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="ifood-card text-muted">Nenhum módulo ativo no momento.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="ifood-section pt-0">
            <div class="container">
                <h2 class="ifood-section-title">{{ $landing_data['feature_title'] }}</h2>
                <p class="ifood-section-sub">{{ $landing_data['feature_short_description'] }}</p>

                <div class="row g-3">
                    @forelse ($features as $feature)
                        <div class="col-md-6 col-lg-4">
                            <div class="ifood-card">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img class="ifood-feature-icon" src="{{ $feature['image_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $feature['title'] ?? 'feature' }}">
                                    <h6 class="mb-0 fw-bold">{{ $feature['title'] ?? '' }}</h6>
                                </div>
                                <p class="text-muted mb-0">{{ $feature['sub_title'] ?? '' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="ifood-card text-muted">Sem recursos configurados no painel ainda.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        @if ($landing_data['available_zone_status'])
            <section class="ifood-section pt-0">
                <div class="container">
                    <h2 class="ifood-section-title">{{ $landing_data['available_zone_title'] }}</h2>
                    <p class="ifood-section-sub">{{ $landing_data['available_zone_short_description'] }}</p>

                    <div class="ifood-card">
                        @forelse ($zones as $zone)
                            <span class="ifood-pill">{{ $zone['display_name'] }}</span>
                        @empty
                            <span class="text-muted">Nenhuma zona disponível.</span>
                        @endforelse
                    </div>
                </div>
            </section>
        @endif

        <section class="ifood-section pt-0">
            <div class="container">
                <div class="ifood-cta">
                    <h3 class="fw-bold">Página inicial limpa + gestão total no painel</h3>
                    <p class="mb-3 text-white-50">Todas as áreas desta home utilizam dados vindos das configurações e cadastros administrativos.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="cmn--btn" href="{{ route('admin.dashboard') }}">Ir para o painel administrativo</a>
                        <a class="cmn--btn btn--secondary" href="{{ route('contact-us') }}">Falar com suporte</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

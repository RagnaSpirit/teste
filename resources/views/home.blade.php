@extends('layouts.landing.app')

@php($business_name = \App\CentralLogics\Helpers::get_business_settings('business_name'))
@section('title', translate('messages.landing_page') . ' | ' . ($business_name != 'null' ? $business_name : 'Sixam Mart'))

@section('content')
    @php($modules = \App\Models\Module::active()->get())
    @php($features = $landing_data['features'] ?? [])
    @php($zones = $landing_data['available_zone_list'] ?? [])
    @php($banners = $landing_data['promotional_banners'] ?? [])

    <style>
        :root {
            --ifood-red: #ea1d2c;
            --ifood-red-dark: #cf1725;
            --ifood-bg: #f7f7f7;
            --ifood-text: #202020;
            --ifood-muted: #6b7280;
            --ifood-card: #ffffff;
            --ifood-border: #ececec;
        }

        .ifd-page {
            background: var(--ifood-bg);
            color: var(--ifood-text);
        }

        .ifd-container {
            max-width: 1180px;
        }

        .ifd-hero {
            padding: 36px 0 56px;
            background: radial-gradient(circle at top right, #ffe3e6 0%, #fff 42%);
        }

        .ifd-chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            background: #ffe9ec;
            color: var(--ifood-red);
            font-weight: 700;
            font-size: .82rem;
            padding: .45rem .8rem;
            margin-bottom: .7rem;
        }

        .ifd-title {
            font-size: clamp(2rem, 4.2vw, 3.5rem);
            line-height: 1.06;
            font-weight: 800;
            letter-spacing: -.02em;
            margin-bottom: .8rem;
        }

        .ifd-subtitle {
            color: var(--ifood-muted);
            font-size: 1.04rem;
            max-width: 640px;
        }

        .ifd-search {
            margin-top: 1.1rem;
            display: flex;
            gap: .55rem;
            background: #fff;
            border: 1px solid var(--ifood-border);
            border-radius: 14px;
            padding: .5rem;
            box-shadow: 0 8px 26px rgba(32, 32, 32, .05);
        }

        .ifd-search input {
            flex: 1;
            border: none;
            outline: none;
            padding: .65rem .75rem;
            background: transparent;
        }

        .ifd-btn-red {
            border: none;
            border-radius: 10px;
            background: var(--ifood-red);
            color: #fff;
            font-weight: 700;
            padding: .62rem .95rem;
            transition: .2s ease;
        }

        .ifd-btn-red:hover {background: var(--ifood-red-dark); color: #fff;}

        .ifd-preview {
            background: #fff;
            border: 1px solid var(--ifood-border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 28px rgba(17, 24, 39, .08);
        }

        .ifd-preview-body {
            padding: .9rem 1rem 1rem;
        }

        .ifd-mini-list {
            list-style: none;
            margin: .2rem 0 0;
            padding: 0;
        }

        .ifd-mini-list li {
            color: #374151;
            border-bottom: 1px dashed #ededed;
            padding: .5rem 0;
            font-size: .92rem;
        }

        .ifd-mini-list li:last-child {border-bottom: 0;}

        .ifd-section {
            padding: 42px 0;
        }

        .ifd-heading {
            font-size: 1.7rem;
            font-weight: 800;
            margin-bottom: .2rem;
        }

        .ifd-heading-sub {
            color: var(--ifood-muted);
            margin-bottom: 1.25rem;
        }

        .ifd-card {
            background: var(--ifood-card);
            border: 1px solid var(--ifood-border);
            border-radius: 16px;
            padding: 1rem;
            height: 100%;
            transition: .2s ease;
        }

        .ifd-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(17, 24, 39, .08);
        }

        .ifd-category-icon {
            width: 48px;
            height: 48px;
            object-fit: contain;
            margin-bottom: .8rem;
        }

        .ifd-banner {
            display: block;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--ifood-border);
            min-height: 150px;
            background: #fff;
        }

        .ifd-banner img {
            width: 100%;
            height: 100%;
            min-height: 150px;
            object-fit: cover;
        }

        .ifd-pill {
            display: inline-block;
            border-radius: 999px;
            border: 1px solid #ffd7db;
            background: #fff;
            color: #a11a25;
            font-weight: 600;
            font-size: .85rem;
            padding: .37rem .72rem;
            margin: .25rem;
        }

        .ifd-cta {
            background: #1f2937;
            border-radius: 20px;
            color: #fff;
            padding: 1.8rem;
        }

        .ifd-cta p {color: rgba(255, 255, 255, .82);}

        @media (max-width: 991px) {
            .ifd-hero {padding-top: 20px;}
        }
    </style>

    <main class="ifd-page">
        <section class="ifd-hero">
            <div class="container ifd-container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="ifd-chip">Estilo iFood + conteúdo gerenciado no painel</span>
                        <h1 class="ifd-title">{{ $landing_data['fixed_header_title'] }}</h1>
                        <p class="ifd-subtitle">{{ $landing_data['fixed_header_sub_title'] }}</p>

                        <div class="ifd-search">
                            <input type="text" readonly value="Tudo desta home é controlado no painel administrativo (módulos, destaques, zonas e banners)." />
                            <a class="ifd-btn-red text-decoration-none" href="{{ route('admin.dashboard') }}">Abrir painel</a>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a class="cmn--btn" href="{{ route('restaurant.create') }}">{{ translate('messages.vendor_registration') }}</a>
                            <a class="cmn--btn btn--secondary" href="{{ route('deliveryman.create') }}">{{ translate('messages.deliveryman_registration') }}</a>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="ifd-preview">
                            <img class="w-100 onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}" src="{{ \App\CentralLogics\Helpers::logoFullUrl() }}" alt="Brand">
                            <div class="ifd-preview-body">
                                <strong>Operação unificada</strong>
                                <ul class="ifd-mini-list">
                                    <li>Home visual limpa e objetiva</li>
                                    <li>Dados vindo do admin sem hardcode</li>
                                    <li>Escalável para crescimento do catálogo</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="ifd-section pt-0">
            <div class="container ifd-container">
                <h2 class="ifd-heading">Categorias da plataforma</h2>
                <p class="ifd-heading-sub">Visual em grid semelhante aos cards de descoberta da home do iFood.</p>

                <div class="row g-3">
                    @forelse ($modules as $module)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="ifd-card text-center">
                                <img class="ifd-category-icon onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/100x100/2.png') }}" src="{{ $module['icon_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $module->module_name }}">
                                <h6 class="fw-bold mb-1">{{ translate("messages.{$module->module_name}") }}</h6>
                                <small class="text-muted">{!! \Illuminate\Support\Str::limit(strip_tags($module->description ?? ''), 70) !!}</small>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="ifd-card text-muted">Nenhum módulo ativo no momento.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="ifd-section pt-0">
            <div class="container ifd-container">
                <h2 class="ifd-heading">Destaques promocionais</h2>
                <p class="ifd-heading-sub">Blocos visuais para campanhas gerenciadas pelo painel.</p>

                <div class="row g-3">
                    @forelse ($banners as $banner)
                        <div class="col-md-6 col-lg-4">
                            <a class="ifd-banner" href="javascript:void(0)">
                                <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $banner['image_full_url'] ?? asset('public/assets/admin/img/900x400/img1.jpg') }}" alt="banner">
                            </a>
                        </div>
                    @empty
                        <div class="col-md-6 col-lg-4">
                            <div class="ifd-banner d-flex align-items-center justify-content-center text-muted">Sem banners ativos</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="ifd-section pt-0">
            <div class="container ifd-container">
                <h2 class="ifd-heading">{{ $landing_data['feature_title'] }}</h2>
                <p class="ifd-heading-sub">{{ $landing_data['feature_short_description'] }}</p>

                <div class="row g-3">
                    @forelse ($features as $feature)
                        <div class="col-md-6 col-lg-4">
                            <div class="ifd-card">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img class="ifd-category-icon" style="width:40px;height:40px" src="{{ $feature['image_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $feature['title'] ?? '' }}">
                                    <h6 class="mb-0 fw-bold">{{ $feature['title'] ?? '' }}</h6>
                                </div>
                                <p class="text-muted mb-0">{{ $feature['sub_title'] ?? '' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="ifd-card text-muted">Sem recursos configurados no painel ainda.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        @if ($landing_data['available_zone_status'])
            <section class="ifd-section pt-0">
                <div class="container ifd-container">
                    <h2 class="ifd-heading">{{ $landing_data['available_zone_title'] }}</h2>
                    <p class="ifd-heading-sub">{{ $landing_data['available_zone_short_description'] }}</p>

                    <div class="ifd-card">
                        @forelse ($zones as $zone)
                            <span class="ifd-pill">{{ $zone['display_name'] }}</span>
                        @empty
                            <span class="text-muted">Nenhuma zona disponível.</span>
                        @endforelse
                    </div>
                </div>
            </section>
        @endif

        <section class="ifd-section pt-0 pb-5">
            <div class="container ifd-container">
                <div class="ifd-cta">
                    <h3 class="fw-bold mb-2">Layout próximo ao iFood, com gestão central no admin</h3>
                    <p class="mb-3">Você atualiza conteúdo pelo painel e a home reflete automaticamente sem precisar mexer na estrutura da página.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="cmn--btn" href="{{ route('admin.dashboard') }}">Gerenciar no painel</a>
                        <a class="cmn--btn btn--secondary" href="{{ route('contact-us') }}">Falar com suporte</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

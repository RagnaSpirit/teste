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
            --if-red: #ea1d2c;
            --if-red-700: #cf1725;
            --if-dark: #202020;
            --if-muted: #717171;
            --if-bg: #f7f7f7;
            --if-card: #ffffff;
            --if-border: #ebebeb;
        }

        body {background: var(--if-bg);}

        /* ===== Topo (mais fiel ao estilo iFood) ===== */
        header .navbar-bottom {
            background: #fff;
            border-bottom: 1px solid #efefef;
            position: sticky;
            top: 0;
            z-index: 200;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .03);
        }

        header .navbar-bottom-wrapper {min-height: 72px;}

        header .logo img {
            height: 34px;
            width: auto;
            object-fit: contain;
        }

        header .menu>li>a {
            color: #3a3a3a;
            font-weight: 600;
            border-radius: 999px;
            padding: .48rem .95rem;
            transition: .2s ease;
        }

        header .menu>li>a.active,
        header .menu>li>a:hover {
            background: #ffe6e9;
            color: var(--if-red);
        }

        .if-home {
            color: var(--if-dark);
            background: var(--if-bg);
        }

        .if-wrap {max-width: 1200px;}

        .if-hero {
            padding: 38px 0 24px;
            background: radial-gradient(1400px 600px at 90% 0%, #ffe7ea 0%, #fff 50%);
        }

        .if-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: #ffe8eb;
            color: var(--if-red);
            border-radius: 999px;
            padding: .4rem .82rem;
            font-size: .82rem;
            font-weight: 800;
            margin-bottom: .75rem;
        }

        .if-title {
            font-size: clamp(2rem, 4vw, 3.35rem);
            line-height: 1.05;
            letter-spacing: -.02em;
            font-weight: 800;
            margin-bottom: .65rem;
        }

        .if-subtitle {
            color: var(--if-muted);
            max-width: 640px;
            margin-bottom: 1.1rem;
            font-size: 1.03rem;
        }

        .if-search {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: .5rem;
            box-shadow: 0 8px 24px rgba(17, 24, 39, .06);
            margin-bottom: .8rem;
        }

        .if-search input {
            flex: 1;
            border: none;
            outline: none;
            padding: .62rem .7rem;
            font-size: .94rem;
            background: transparent;
        }

        .if-btn-red {
            background: var(--if-red);
            color: #fff;
            border-radius: 10px;
            border: 0;
            font-weight: 700;
            padding: .62rem .95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .2s ease;
        }

        .if-btn-red:hover {background: var(--if-red-700); color: #fff;}

        .if-preview {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 14px 28px rgba(0, 0, 0, .06);
        }

        .if-preview-body {padding: .9rem 1rem 1rem;}
        .if-preview ul {padding-left: 1rem; margin-bottom: 0; color: #444;}

        /* ===== Menus / categorias ===== */
        .if-block {padding: 20px 0 38px;}

        .if-heading {
            font-size: 1.62rem;
            font-weight: 800;
            margin-bottom: .2rem;
        }

        .if-heading-sub {color: var(--if-muted); margin-bottom: 1.15rem;}

        .if-cat-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
            gap: 12px;
        }

        .if-cat-card {
            background: var(--if-card);
            border: 1px solid var(--if-border);
            border-radius: 15px;
            padding: .95rem .75rem;
            text-align: center;
            transition: .2s ease;
        }

        .if-cat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .06);
        }

        .if-cat-card img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            margin-bottom: .5rem;
        }

        .if-cat-card h6 {margin-bottom: .25rem; font-weight: 700;}
        .if-cat-card p {margin-bottom: 0; color: var(--if-muted); font-size: .8rem;}

        /* ===== Banners ===== */
        .if-banner-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 12px;
        }

        .if-banner,
        .if-banner-stack {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 16px;
            overflow: hidden;
            min-height: 150px;
        }

        .if-banner img,
        .if-banner-stack img {
            width: 100%;
            height: 100%;
            min-height: 150px;
            object-fit: cover;
            display: block;
        }

        .if-banner-stack-wrap {
            display: grid;
            gap: 12px;
            grid-template-rows: repeat(2, minmax(140px, 1fr));
        }

        /* ===== Boxes / cards ===== */
        .if-box-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 12px;
        }

        .if-box {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: 1rem;
        }

        .if-box-top {
            display: flex;
            align-items: center;
            gap: .55rem;
            margin-bottom: .55rem;
        }

        .if-box-top img {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            object-fit: cover;
        }

        .if-zone-card {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: .9rem;
        }

        .if-zone-pill {
            display: inline-block;
            border-radius: 999px;
            border: 1px solid #ffd6db;
            color: #9f1b27;
            background: #fff;
            font-size: .85rem;
            font-weight: 600;
            padding: .35rem .68rem;
            margin: .2rem;
        }

        /* ===== Rodapé ===== */
        footer .newsletter-section {display: none;}

        footer .footer-bottom {
            background: #fff;
            border-top: 1px solid var(--if-border);
            padding-top: 20px;
            margin-top: 10px;
        }

        footer .footer-wrapper {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            gap: 18px;
        }

        footer .footer-widget .subtitle,
        footer .footer-widget .txt,
        footer .footer-widget a,
        footer .copyright {
            color: #353535 !important;
        }

        footer .footer-widget ul li {margin-bottom: .45rem;}

        footer .social-icon img {
            filter: grayscale(.2);
            opacity: .92;
        }

        footer .copyright {
            border-top: 1px solid #efefef;
            padding-top: 12px;
            margin-top: 14px !important;
        }

        @media (max-width: 991px) {
            .if-banner-grid {grid-template-columns: 1fr;}
            footer .footer-wrapper {grid-template-columns: 1fr;}
            header .navbar-bottom-wrapper {min-height: 64px;}
        }
    </style>

    <main class="if-home">
        <section class="if-hero">
            <div class="container if-wrap">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="if-pill">Visual iFood + gestão no painel admin</span>
                        <h1 class="if-title">{{ $landing_data['fixed_header_title'] }}</h1>
                        <p class="if-subtitle">{{ $landing_data['fixed_header_sub_title'] }}</p>

                        <div class="if-search">
                            <input type="text" readonly value="Categorias, banners, boxes, zonas e textos são atualizados pelo painel administrativo." />
                            <a class="if-btn-red" href="{{ route('admin.dashboard') }}">Painel admin</a>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a class="cmn--btn" href="{{ route('restaurant.create') }}">{{ translate('messages.vendor_registration') }}</a>
                            <a class="cmn--btn btn--secondary" href="{{ route('deliveryman.create') }}">{{ translate('messages.deliveryman_registration') }}</a>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="if-preview">
                            <img class="w-100 onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}" src="{{ \App\CentralLogics\Helpers::logoFullUrl() }}" alt="logo">
                            <div class="if-preview-body">
                                <strong>Home organizada</strong>
                                <ul>
                                    <li>Cards com leitura rápida</li>
                                    <li>Banners em destaque</li>
                                    <li>Menu superior e rodapé refinados</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="if-block">
            <div class="container if-wrap">
                <h2 class="if-heading">Menus da plataforma</h2>
                <p class="if-heading-sub">Categorias em formato de caixas, semelhante ao estilo de descoberta da home iFood.</p>

                <div class="if-cat-row">
                    @forelse ($modules as $module)
                        <div class="if-cat-card">
                            <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/100x100/2.png') }}" src="{{ $module['icon_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $module->module_name }}">
                            <h6>{{ translate("messages.{$module->module_name}") }}</h6>
                            <p>{!! \Illuminate\Support\Str::limit(strip_tags($module->description ?? ''), 42) !!}</p>
                        </div>
                    @empty
                        <div class="if-cat-card">
                            <h6>Nenhum módulo ativo</h6>
                            <p>Ative módulos no painel para aparecer aqui.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="if-block pt-0">
            <div class="container if-wrap">
                <h2 class="if-heading">Banners em destaque</h2>
                <p class="if-heading-sub">Layout de banners principal + laterais para campanha, com visual fiel de vitrine.</p>

                <div class="if-banner-grid">
                    <div class="if-banner">
                        @php($bannerMain = $banners[0]['image_full_url'] ?? asset('public/assets/admin/img/900x400/img1.jpg'))
                        <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $bannerMain }}" alt="banner principal">
                    </div>

                    <div class="if-banner-stack-wrap">
                        <div class="if-banner-stack">
                            @php($bannerTwo = $banners[1]['image_full_url'] ?? $bannerMain)
                            <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $bannerTwo }}" alt="banner secundário">
                        </div>
                        <div class="if-banner-stack">
                            @php($bannerThree = $banners[2]['image_full_url'] ?? $bannerMain)
                            <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $bannerThree }}" alt="banner secundário 2">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="if-block pt-0">
            <div class="container if-wrap">
                <h2 class="if-heading">Boxes de vantagens</h2>
                <p class="if-heading-sub">Blocos limpos para destacar recursos da operação, também administráveis via painel.</p>

                <div class="if-box-grid">
                    @forelse ($features as $feature)
                        <div class="if-box">
                            <div class="if-box-top">
                                <img src="{{ $feature['image_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $feature['title'] ?? '' }}">
                                <h6 class="mb-0 fw-bold">{{ $feature['title'] ?? '' }}</h6>
                            </div>
                            <p class="text-muted mb-0">{{ $feature['sub_title'] ?? '' }}</p>
                        </div>
                    @empty
                        <div class="if-box">
                            <h6 class="fw-bold">Sem boxes ativos</h6>
                            <p class="text-muted mb-0">Cadastre itens de destaque no painel para exibir aqui.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        @if ($landing_data['available_zone_status'])
            <section class="if-block pt-0">
                <div class="container if-wrap">
                    <h2 class="if-heading">{{ $landing_data['available_zone_title'] }}</h2>
                    <p class="if-heading-sub">{{ $landing_data['available_zone_short_description'] }}</p>

                    <div class="if-zone-card">
                        @forelse ($zones as $zone)
                            <span class="if-zone-pill">{{ $zone['display_name'] }}</span>
                        @empty
                            <span class="text-muted">Nenhuma zona cadastrada.</span>
                        @endforelse
                    </div>
                </div>
            </section>
        @endif
    </main>
@endsection

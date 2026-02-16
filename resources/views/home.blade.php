@extends('layouts.landing.app')

@php($business_name = \App\CentralLogics\Helpers::get_business_settings('business_name'))
@section('title', translate('messages.landing_page') . ' | ' . ($business_name != 'null' ? $business_name : 'Sixam Mart'))

@section('content')
    @php($modules = \App\Models\Module::active()->get())
    @php($features = $landing_data['features'] ?? [])
    @php($zones = $landing_data['available_zone_list'] ?? [])
    @php($banners = $landing_data['promotional_banners'] ?? [])

    @php($bannerMain = $banners[0]['image_full_url'] ?? asset('public/assets/admin/img/900x400/img1.jpg'))
    @php($bannerSecond = $banners[1]['image_full_url'] ?? $bannerMain)
    @php($bannerThird = $banners[2]['image_full_url'] ?? $bannerMain)

    <style>
        :root {
            --if-red: #ea1d2c;
            --if-text: #1f1f1f;
            --if-muted: #667085;
            --if-bg: #f5f5f5;
            --if-card: #fff;
            --if-border: #ececec;
        }

        body { background: var(--if-bg); }

        header .navbar-bottom {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #fff;
            border-bottom: 1px solid #efefef;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .03);
        }

        header .navbar-bottom-wrapper { min-height: 72px; }
        header .logo img { height: 32px; width: auto; object-fit: contain; }

        header .menu>li>a {
            color: #3d3d3d;
            font-weight: 700;
            border-radius: 999px;
            padding: .45rem .9rem;
            transition: .2s;
        }

        header .menu>li>a.active,
        header .menu>li>a:hover {
            color: var(--if-red);
            background: #ffe8eb;
        }

        footer .newsletter-section { display: none; }
        footer .footer-bottom {
            background: #fff;
            border-top: 1px solid var(--if-border);
            margin-top: 20px;
            padding-top: 22px;
        }
        footer .footer-wrapper {
            display: grid;
            grid-template-columns: 1.3fr 1fr 1fr;
            gap: 18px;
        }
        footer .footer-widget .subtitle,
        footer .footer-widget .txt,
        footer .footer-widget a,
        footer .copyright { color: #333 !important; }
        footer .copyright { border-top: 1px solid #efefef; margin-top: 12px !important; padding-top: 10px; }

        .if-wrapper { max-width: 1220px; }
        .if-home { color: var(--if-text); }

        /* HERO super fiel ao conceito iFood */
        .if-hero {
            padding: 36px 0 20px;
            background: radial-gradient(1100px 450px at 88% 0%, #ffe3e7 0%, #fff 50%);
        }

        .if-hero h1 {
            font-size: clamp(2rem, 4.5vw, 3.8rem);
            font-weight: 800;
            line-height: 1.03;
            letter-spacing: -.02em;
            margin-bottom: .55rem;
        }

        .if-hero p { color: var(--if-muted); font-size: 1.05rem; max-width: 620px; }

        .if-address {
            display: flex;
            align-items: center;
            gap: .55rem;
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: .5rem;
            margin-top: .85rem;
            box-shadow: 0 10px 22px rgba(17,24,39,.06);
        }

        .if-address input {
            flex: 1;
            border: 0;
            outline: none;
            padding: .62rem .7rem;
            font-size: .94rem;
            background: transparent;
        }

        .if-btn-red {
            border: 0;
            border-radius: 10px;
            background: var(--if-red);
            color: #fff;
            font-weight: 700;
            padding: .62rem .95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .if-btn-red:hover { color: #fff; opacity: .94; }

        .if-hero-side {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 14px 28px rgba(0,0,0,.07);
        }

        .if-hero-side-body { padding: 1rem; }

        .if-quick-grid {
            margin-top: 12px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .if-quick {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 12px;
            padding: .72rem;
            font-size: .87rem;
            color: #3a3a3a;
        }

        /* Menus estilo cards coloridos */
        .if-section { padding: 22px 0 30px; }
        .if-title { font-size: 1.7rem; font-weight: 800; margin-bottom: .2rem; }
        .if-sub { color: var(--if-muted); margin-bottom: .95rem; }

        .if-modules {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
        }

        .if-module {
            border-radius: 16px;
            padding: 1rem .8rem;
            border: 1px solid #f0f0f0;
            background: #fff;
            text-align: center;
            transition: .2s ease;
        }

        .if-module:nth-child(4n+1) { background: linear-gradient(180deg, #fff6f6 0%, #fff 100%); }
        .if-module:nth-child(4n+2) { background: linear-gradient(180deg, #fff8ee 0%, #fff 100%); }
        .if-module:nth-child(4n+3) { background: linear-gradient(180deg, #f2fbff 0%, #fff 100%); }
        .if-module:nth-child(4n+4) { background: linear-gradient(180deg, #f3fff7 0%, #fff 100%); }

        .if-module:hover { transform: translateY(-3px); box-shadow: 0 10px 18px rgba(0,0,0,.06); }
        .if-module img { width: 48px; height: 48px; object-fit: contain; margin-bottom: .55rem; }
        .if-module h6 { margin-bottom: .25rem; font-weight: 700; }
        .if-module p { margin: 0; color: var(--if-muted); font-size: .8rem; }

        /* Banners grandes como iFood */
        .if-banner-grid {
            display: grid;
            grid-template-columns: 2.1fr 1fr;
            gap: 12px;
        }

        .if-banner,
        .if-banner-sm {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 16px;
            overflow: hidden;
            min-height: 160px;
        }

        .if-banner img,
        .if-banner-sm img {
            width: 100%;
            height: 100%;
            min-height: 160px;
            object-fit: cover;
            display: block;
        }

        .if-banner-col {
            display: grid;
            grid-template-rows: repeat(2, minmax(150px, 1fr));
            gap: 12px;
        }

        /* cards de conteúdo */
        .if-feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 12px;
        }

        .if-feature {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: 1rem;
        }

        .if-feature-head { display: flex; align-items: center; gap: .55rem; margin-bottom: .55rem; }
        .if-feature-head img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }

        .if-zone {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: .9rem;
        }

        .if-zone-pill {
            display: inline-block;
            border-radius: 999px;
            border: 1px solid #ffd7dc;
            color: #9f1b27;
            font-size: .85rem;
            font-weight: 600;
            background: #fff;
            padding: .34rem .66rem;
            margin: .18rem;
        }

        .if-final {
            background: linear-gradient(145deg, #242424 0%, #363636 100%);
            color: #fff;
            border-radius: 18px;
            padding: 1.5rem;
        }

        .if-final p { color: rgba(255,255,255,.85); margin-bottom: .85rem; }

        @media (max-width: 991px) {
            .if-banner-grid { grid-template-columns: 1fr; }
            footer .footer-wrapper { grid-template-columns: 1fr; }
            header .navbar-bottom-wrapper { min-height: 64px; }
        }
    </style>

    <main class="if-home">
        <section class="if-hero">
            <div class="container if-wrapper">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <h1>{{ $landing_data['fixed_header_title'] }}</h1>
                        <p>{{ $landing_data['fixed_header_sub_title'] }}</p>

                        <div class="if-address">
                            <input type="text" readonly value="Organização total da home com edição no painel administrativo" />
                            <a class="if-btn-red" href="{{ route('admin.dashboard') }}">Abrir painel</a>
                        </div>

                        <div class="if-quick-grid">
                            <div class="if-quick">Menus por módulo</div>
                            <div class="if-quick">Banners de campanha</div>
                            <div class="if-quick">Boxes de benefício</div>
                            <div class="if-quick">Zonas dinâmicas</div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a class="cmn--btn" href="{{ route('restaurant.create') }}">{{ translate('messages.vendor_registration') }}</a>
                            <a class="cmn--btn btn--secondary" href="{{ route('deliveryman.create') }}">{{ translate('messages.deliveryman_registration') }}</a>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="if-hero-side">
                            <img class="w-100 onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}" src="{{ \App\CentralLogics\Helpers::logoFullUrl() }}" alt="logo">
                            <div class="if-hero-side-body">
                                <strong>Visual estilo marketplace</strong>
                                <p class="text-muted mb-0 mt-2">Topo, menus, banners, boxes e rodapé reorganizados para ficar no padrão visual solicitado.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="if-section">
            <div class="container if-wrapper">
                <h2 class="if-title">Menus</h2>
                <p class="if-sub">Navegação por categorias em cards grandes e escaneáveis.</p>

                <div class="if-modules">
                    @forelse ($modules as $module)
                        <div class="if-module">
                            <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/100x100/2.png') }}" src="{{ $module['icon_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $module->module_name }}">
                            <h6>{{ translate("messages.{$module->module_name}") }}</h6>
                            <p>{!! \Illuminate\Support\Str::limit(strip_tags($module->description ?? ''), 54) !!}</p>
                        </div>
                    @empty
                        <div class="if-module">
                            <h6>Nenhum módulo ativo</h6>
                            <p>Ative módulos no painel para exibir aqui.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="if-section pt-0">
            <div class="container if-wrapper">
                <h2 class="if-title">Banners</h2>
                <p class="if-sub">Vitrine principal com destaque e peças secundárias.</p>

                <div class="if-banner-grid">
                    <div class="if-banner">
                        <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $bannerMain }}" alt="banner principal">
                    </div>
                    <div class="if-banner-col">
                        <div class="if-banner-sm">
                            <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $bannerSecond }}" alt="banner 2">
                        </div>
                        <div class="if-banner-sm">
                            <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $bannerThird }}" alt="banner 3">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="if-section pt-0">
            <div class="container if-wrapper">
                <h2 class="if-title">Boxes</h2>
                <p class="if-sub">Blocos de vantagens e informações em formato de cards.</p>

                <div class="if-feature-grid">
                    @forelse ($features as $feature)
                        <div class="if-feature">
                            <div class="if-feature-head">
                                <img src="{{ $feature['image_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $feature['title'] ?? '' }}">
                                <h6 class="fw-bold mb-0">{{ $feature['title'] ?? '' }}</h6>
                            </div>
                            <p class="text-muted mb-0">{{ $feature['sub_title'] ?? '' }}</p>
                        </div>
                    @empty
                        <div class="if-feature">
                            <h6 class="fw-bold">Sem boxes</h6>
                            <p class="text-muted mb-0">Cadastre itens no painel.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        @if ($landing_data['available_zone_status'])
            <section class="if-section pt-0">
                <div class="container if-wrapper">
                    <h2 class="if-title">{{ $landing_data['available_zone_title'] }}</h2>
                    <p class="if-sub">{{ $landing_data['available_zone_short_description'] }}</p>

                    <div class="if-zone">
                        @forelse ($zones as $zone)
                            <span class="if-zone-pill">{{ $zone['display_name'] }}</span>
                        @empty
                            <span class="text-muted">Nenhuma zona disponível.</span>
                        @endforelse
                    </div>
                </div>
            </section>
        @endif

        <section class="if-section pt-0 pb-4">
            <div class="container if-wrapper">
                <div class="if-final">
                    <h3 class="fw-bold mb-2">Agora sim: estrutura visual completa e organizada</h3>
                    <p>Com todo o conteúdo administrável no painel, sem perder a identidade de marketplace moderno.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="cmn--btn" href="{{ route('admin.dashboard') }}">Gerenciar no painel</a>
                        <a class="cmn--btn btn--secondary" href="{{ route('contact-us') }}">Suporte</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

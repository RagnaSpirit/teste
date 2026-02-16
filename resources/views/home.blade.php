@extends('layouts.landing.app')

@php($business_name = \App\CentralLogics\Helpers::get_business_settings('business_name'))
@section('title', translate('messages.landing_page') . ' | ' . ($business_name != 'null' ? $business_name : 'Sixam Mart'))

@section('content')
    @php($modules = \App\Models\Module::active()->get())
    @php($features = $landing_data['features'] ?? [])
    @php($zones = $landing_data['available_zone_list'] ?? [])
    @php($banners = $landing_data['promotional_banners'] ?? [])
    @php($business_logo = \App\CentralLogics\Helpers::logoFullUrl())

    <style>
        :root {
            --if-red: #ea1d2c;
            --if-red-soft: #ffe9ec;
            --if-text: #1f1f1f;
            --if-muted: #6b7280;
            --if-bg: #f7f7f7;
            --if-card: #ffffff;
            --if-border: #ececec;
        }

        body { background: var(--if-bg); }

        header .navbar-bottom {
            position: sticky;
            top: 0;
            z-index: 99;
            background: #fff;
            border-bottom: 1px solid #efefef;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .03);
        }

        header .navbar-bottom-wrapper { min-height: 72px; }
        header .logo img { height: 34px; width: auto; object-fit: contain; }

        header .menu > li > a {
            color: #3f3f46;
            padding: .45rem .9rem;
            border-radius: 999px;
            font-weight: 600;
            transition: .2s ease;
        }

        header .menu > li > a.active,
        header .menu > li > a:hover {
            color: var(--if-red);
            background: var(--if-red-soft);
        }

        .if-home { color: var(--if-text); }
        .if-wrap { max-width: 1200px; }

        .if-hero {
            padding: 34px 0 22px;
            background: radial-gradient(1200px 540px at 95% 0%, #ffe5e8 0%, #fff 52%);
        }

        .if-chip {
            display: inline-block;
            border-radius: 999px;
            background: var(--if-red-soft);
            color: var(--if-red);
            font-weight: 800;
            font-size: .8rem;
            padding: .38rem .8rem;
            margin-bottom: .75rem;
        }

        .if-title {
            font-size: clamp(2rem, 4.4vw, 3.6rem);
            line-height: 1.04;
            font-weight: 800;
            letter-spacing: -.02em;
            margin-bottom: .55rem;
        }

        .if-sub {
            color: var(--if-muted);
            font-size: 1.03rem;
            max-width: 650px;
            margin-bottom: 1rem;
        }

        .if-locate {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem;
            box-shadow: 0 10px 24px rgba(17, 24, 39, .06);
        }

        .if-locate input {
            border: 0;
            outline: none;
            background: transparent;
            flex: 1;
            padding: .62rem .68rem;
            font-size: .94rem;
        }

        .if-btn {
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

        .if-btn:hover { color: #fff; opacity: .92; }

        .if-hero-card {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(17, 24, 39, .08);
        }

        .if-hero-card-body { padding: .95rem 1rem; }

        .if-mini {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }

        .if-mini .item {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 12px;
            padding: .7rem;
            font-size: .86rem;
            color: #3f3f46;
        }

        .if-section { padding: 24px 0 34px; }

        .if-h2 { font-size: 1.6rem; font-weight: 800; margin-bottom: .2rem; }
        .if-p { color: var(--if-muted); margin-bottom: 1rem; }

        .if-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
        }

        .if-cat {
            background: var(--if-card);
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: .9rem .7rem;
            text-align: center;
            transition: .2s ease;
        }

        .if-cat:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 0, 0, .06); }
        .if-cat img { width: 44px; height: 44px; object-fit: contain; margin-bottom: .5rem; }
        .if-cat h6 { margin-bottom: .2rem; font-weight: 700; }
        .if-cat p { margin-bottom: 0; color: var(--if-muted); font-size: .8rem; }

        .if-banner-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 12px;
        }

        .if-banner, .if-banner-sm {
            border-radius: 16px;
            border: 1px solid var(--if-border);
            overflow: hidden;
            background: #fff;
            min-height: 145px;
        }

        .if-banner img, .if-banner-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-height: 145px;
            display: block;
        }

        .if-banner-col { display: grid; gap: 12px; grid-template-rows: repeat(2, minmax(140px, 1fr)); }

        .if-box-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(245px, 1fr));
            gap: 12px;
        }

        .if-box {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: 1rem;
        }

        .if-box-head { display: flex; gap: .55rem; align-items: center; margin-bottom: .55rem; }
        .if-box-head img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }

        .if-zone {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: .9rem;
        }

        .if-zone-pill {
            display: inline-block;
            border-radius: 999px;
            border: 1px solid #ffd8dd;
            color: #9f1a26;
            background: #fff;
            font-size: .85rem;
            padding: .32rem .66rem;
            margin: .2rem;
            font-weight: 600;
        }

        .if-cta {
            background: linear-gradient(145deg, #222 0%, #313131 100%);
            color: #fff;
            border-radius: 18px;
            padding: 1.5rem;
        }

        .if-cta p { color: rgba(255,255,255,.85); margin-bottom: .8rem; }

        footer .newsletter-section { display: none; }
        footer .footer-bottom {
            background: #fff;
            border-top: 1px solid var(--if-border);
            margin-top: 14px;
            padding-top: 20px;
        }

        footer .footer-wrapper {
            display: grid;
            grid-template-columns: 1.25fr 1fr 1fr;
            gap: 16px;
        }

        footer .footer-widget .subtitle,
        footer .footer-widget .txt,
        footer .footer-widget a,
        footer .copyright { color: #353535 !important; }

        footer .copyright {
            border-top: 1px solid #efefef;
            margin-top: 12px !important;
            padding-top: 10px;
        }

        @media (max-width: 991px) {
            .if-banner-grid { grid-template-columns: 1fr; }
            footer .footer-wrapper { grid-template-columns: 1fr; }
            header .navbar-bottom-wrapper { min-height: 64px; }
        }
    </style>

    <main class="if-home">
        <section class="if-hero">
            <div class="container if-wrap">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="if-chip">Home organizada no estilo iFood</span>
                        <h1 class="if-title">{{ $landing_data['fixed_header_title'] }}</h1>
                        <p class="if-sub">{{ $landing_data['fixed_header_sub_title'] }}</p>

                        <div class="if-locate">
                            <input type="text" readonly value="Conteúdo 100% gerenciável no painel: menus, banners, boxes, zonas e textos." />
                            <a class="if-btn" href="{{ route('admin.dashboard') }}">Painel</a>
                        </div>

                        <div class="if-mini">
                            <div class="item">Cadastre lojas e parceiros</div>
                            <div class="item">Controle módulos e campanhas</div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a class="cmn--btn" href="{{ route('restaurant.create') }}">{{ translate('messages.vendor_registration') }}</a>
                            <a class="cmn--btn btn--secondary" href="{{ route('deliveryman.create') }}">{{ translate('messages.deliveryman_registration') }}</a>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="if-hero-card">
                            <img class="w-100 onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}" src="{{ $business_logo }}" alt="logo">
                            <div class="if-hero-card-body">
                                <strong>Visual limpo + performance de gestão</strong>
                                <p class="text-muted mb-0 mt-2">Estrutura da home com foco em descoberta e conversão, mantendo conexão com o painel administrativo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="if-section">
            <div class="container if-wrap">
                <h2 class="if-h2">Menus</h2>
                <p class="if-p">Categorias visuais em blocos rápidos, no padrão de navegação de marketplace.</p>

                <div class="if-categories">
                    @forelse ($modules as $module)
                        <div class="if-cat">
                            <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/100x100/2.png') }}" src="{{ $module['icon_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $module->module_name }}">
                            <h6>{{ translate("messages.{$module->module_name}") }}</h6>
                            <p>{!! \Illuminate\Support\Str::limit(strip_tags($module->description ?? ''), 44) !!}</p>
                        </div>
                    @empty
                        <div class="if-cat">
                            <h6>Nenhum módulo ativo</h6>
                            <p>Ative módulos no painel.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="if-section pt-0">
            <div class="container if-wrap">
                <h2 class="if-h2">Banners</h2>
                <p class="if-p">Destaques principais com hierarquia visual de vitrine.</p>

                @php($bannerMain = $banners[0]['image_full_url'] ?? asset('public/assets/admin/img/900x400/img1.jpg'))
                @php($bannerSecond = $banners[1]['image_full_url'] ?? $bannerMain)
                @php($bannerThird = $banners[2]['image_full_url'] ?? $bannerMain)

                <div class="if-banner-grid">
                    <div class="if-banner">
                        <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $bannerMain }}" alt="banner principal">
                    </div>
                    <div class="if-banner-col">
                        <div class="if-banner-sm">
                            <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $bannerSecond }}" alt="banner secundário">
                        </div>
                        <div class="if-banner-sm">
                            <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" src="{{ $bannerThird }}" alt="banner secundário 2">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="if-section pt-0">
            <div class="container if-wrap">
                <h2 class="if-h2">Boxes de conteúdo</h2>
                <p class="if-p">Blocos informativos e benefícios com atualização pelo admin.</p>

                <div class="if-box-grid">
                    @forelse ($features as $feature)
                        <div class="if-box">
                            <div class="if-box-head">
                                <img src="{{ $feature['image_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $feature['title'] ?? '' }}">
                                <h6 class="fw-bold mb-0">{{ $feature['title'] ?? '' }}</h6>
                            </div>
                            <p class="text-muted mb-0">{{ $feature['sub_title'] ?? '' }}</p>
                        </div>
                    @empty
                        <div class="if-box">
                            <h6 class="fw-bold">Sem boxes cadastrados</h6>
                            <p class="text-muted mb-0">Cadastre no painel para exibir nesta área.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        @if ($landing_data['available_zone_status'])
            <section class="if-section pt-0">
                <div class="container if-wrap">
                    <h2 class="if-h2">{{ $landing_data['available_zone_title'] }}</h2>
                    <p class="if-p">{{ $landing_data['available_zone_short_description'] }}</p>

                    <div class="if-zone">
                        @forelse ($zones as $zone)
                            <span class="if-zone-pill">{{ $zone['display_name'] }}</span>
                        @empty
                            <span class="text-muted">Nenhuma zona disponível no momento.</span>
                        @endforelse
                    </div>
                </div>
            </section>
        @endif

        <section class="if-section pt-0 pb-4">
            <div class="container if-wrap">
                <div class="if-cta">
                    <h3 class="fw-bold mb-2">Organização completa da home + integração administrativa</h3>
                    <p>Essa estrutura mantém o visual de marketplace com total controle do conteúdo no painel.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="cmn--btn" href="{{ route('admin.dashboard') }}">Gerenciar agora</a>
                        <a class="cmn--btn btn--secondary" href="{{ route('contact-us') }}">Suporte</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

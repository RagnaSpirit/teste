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
            --if-bg: #f7f7f7;
            --if-text: #232323;
            --if-muted: #6b7280;
            --if-border: #e9e9e9;
        }

        body { background: var(--if-bg); }
        .ifood-home { color: var(--if-text); }
        .if-wrap { max-width: 1120px; }

        /* header */
        header .navbar-bottom {
            background: #fff;
            border-bottom: 1px solid #ececec;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 3px 12px rgba(0,0,0,.03);
        }
        header .navbar-bottom-wrapper { min-height: 76px; }
        header .logo img { height: 34px; width: auto; object-fit: contain; }
        header .menu > li > a {
            color: #404040;
            font-weight: 600;
            border-radius: 999px;
            padding: .48rem .92rem;
            transition: .2s ease;
        }
        header .menu > li > a.active,
        header .menu > li > a:hover { background: #ffe7ea; color: var(--if-red); }

        /* hero */
        .if-hero {
            padding: 50px 0 26px;
            text-align: center;
        }
        .if-hero h1 {
            font-size: clamp(2rem, 4vw, 3.25rem);
            font-weight: 800;
            line-height: 1.08;
            margin-bottom: .5rem;
        }
        .if-hero p {
            font-size: 1.08rem;
            color: var(--if-muted);
            margin-bottom: 1.15rem;
        }

        .if-search-wrap {
            max-width: 760px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
        }
        .if-search {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 10px;
            padding: .8rem 1rem;
            font-size: 1rem;
            outline: none;
        }
        .if-search-btn {
            border: 0;
            border-radius: 10px;
            background: var(--if-red);
            color: #fff;
            font-weight: 700;
            min-width: 170px;
            padding: .8rem 1rem;
        }

        /* big cards */
        .if-main-cards {
            margin-top: 28px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .if-main-card {
            border-radius: 20px;
            min-height: 210px;
            padding: 1.2rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .if-main-card h3 { font-size: 2.1rem; font-weight: 800; margin: 0; }
        .if-main-card a {
            align-self: flex-start;
            text-decoration: none;
            border-radius: 999px;
            padding: .55rem 1rem;
            font-weight: 700;
            background: rgba(0,0,0,.15);
            color: #fff;
        }
        .if-card-red { background: linear-gradient(135deg, #f3122f 0%, #ea1d2c 100%); }
        .if-card-green { background: linear-gradient(135deg, #b7d743 0%, #a6c938 100%); }

        .if-main-card img {
            position: absolute;
            right: 10px;
            bottom: 0;
            width: 45%;
            max-height: 90%;
            object-fit: contain;
            opacity: .95;
        }

        /* chips */
        .if-chip-row {
            margin-top: 18px;
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 14px;
        }
        .if-chip {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 12px;
            padding: .7rem;
            text-align: center;
            font-weight: 700;
        }

        .if-divider { border: 0; border-top: 1px solid #e5e7eb; margin: 34px 0; }

        /* section cards */
        .if-title { font-size: 2rem; font-weight: 800; margin-bottom: .8rem; }
        .if-grid-5 { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; }

        .if-brand {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 12px;
            padding: 1rem .85rem;
            min-height: 98px;
            display: flex;
            align-items: center;
            gap: .65rem;
        }
        .if-brand .dot {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f2f2f2;
            flex-shrink: 0;
        }
        .if-brand h6 { margin: 0; font-size: 1rem; font-weight: 700; }
        .if-brand small { color: var(--if-muted); }

        .if-promo-grid {
            margin-top: 14px;
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 12px;
        }
        .if-promo {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--if-border);
            background: #fff;
            min-height: 124px;
        }
        .if-promo img { width: 100%; height: 100%; object-fit: cover; }

        .if-cta-grid {
            margin-top: 34px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .if-cta {
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 18px;
            padding: 1.4rem;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .if-cta h3 {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.15;
            margin: 0 0 .7rem;
        }
        .if-cta p { color: var(--if-muted); }
        .if-cta .btn {
            align-self: flex-start;
            background: var(--if-red);
            color: #fff;
            border-radius: 10px;
            font-weight: 700;
            padding: .55rem 1.1rem;
        }

        .if-zones {
            margin-top: 24px;
            background: #fff;
            border: 1px solid var(--if-border);
            border-radius: 14px;
            padding: .9rem;
        }
        .if-zones .pill {
            display: inline-block;
            margin: .2rem;
            padding: .35rem .68rem;
            border-radius: 999px;
            border: 1px solid #ffd6db;
            color: #a31d2a;
            font-weight: 600;
            font-size: .86rem;
            background: #fff;
        }

        /* footer clean, non-centered */
        footer .newsletter-section { display: none; }
        footer .footer-bottom {
            background: #efefef;
            border-top: 1px solid #dfdfdf;
            padding-top: 30px;
            margin-top: 38px;
        }
        footer .footer-wrapper {
            max-width: 1120px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.3fr 1fr 1fr;
            gap: 24px;
            align-items: flex-start;
            text-align: left;
            padding-left: 0 !important;
        }
        footer .footer-widget,
        footer .footer-widget ul,
        footer .footer-widget li,
        footer .footer-widget a,
        footer .footer-widget .txt,
        footer .footer-widget .subtitle { text-align: left !important; color: #4b5563 !important; }
        footer .footer-widget .subtitle { color: #111827 !important; }
        footer .copyright {
            max-width: 1120px;
            margin: 14px auto 0 !important;
            text-align: left !important;
            border-top: 1px solid #d9d9d9;
            color: #6b7280 !important;
            padding-top: 12px;
        }

        @media (max-width: 991px) {
            .if-main-cards,
            .if-chip-row,
            .if-grid-5,
            .if-promo-grid,
            .if-cta-grid { grid-template-columns: 1fr; }
            .if-search-wrap { grid-template-columns: 1fr; }
            footer .footer-wrapper { grid-template-columns: 1fr; }
        }
    </style>

    <main class="ifood-home">
        <section class="if-hero">
            <div class="container if-wrap">
                <h1>{{ $landing_data['fixed_header_title'] }}</h1>
                <p>{{ $landing_data['fixed_header_sub_title'] }}</p>

                <div class="if-search-wrap">
                    <input class="if-search" type="text" placeholder="Endereço de entrega e número" />
                    <button class="if-search-btn" type="button">Buscar</button>
                </div>

                <div class="if-main-cards">
                    <div class="if-main-card if-card-red">
                        <h3>Restaurante</h3>
                        <a href="{{ route('restaurant.create') }}">Ver opções</a>
                        <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}" src="{{ \App\CentralLogics\Helpers::logoFullUrl() }}" alt="restaurante">
                    </div>
                    <div class="if-main-card if-card-green">
                        <h3>Mercado</h3>
                        <a href="{{ route('restaurant.create') }}">Buscar lojas</a>
                        <img class="onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}" src="{{ $bannerSecond }}" alt="mercado">
                    </div>
                </div>

                <div class="if-chip-row">
                    <div class="if-chip">Bebidas</div>
                    <div class="if-chip">Farmácia</div>
                    <div class="if-chip">Pet shop</div>
                </div>

                <hr class="if-divider">

                <h2 class="if-title">Os melhores restaurantes</h2>
                <div class="if-grid-5">
                    @forelse ($modules->take(5) as $module)
                        <div class="if-brand">
                            <div class="dot"></div>
                            <div>
                                <h6>{{ translate("messages.{$module->module_name}") }}</h6>
                                <small>{{ \Illuminate\Support\Str::limit(strip_tags($module->description ?? ''), 22) }}</small>
                            </div>
                        </div>
                    @empty
                        @for ($i=0; $i<5; $i++)
                            <div class="if-brand"><div class="dot"></div><div><h6>Loja</h6><small>Categoria</small></div></div>
                        @endfor
                    @endforelse
                </div>

                <div class="if-promo-grid">
                    <div class="if-promo"><img src="{{ $bannerMain }}" alt="promo 1"></div>
                    <div class="if-promo"><img src="{{ $bannerSecond }}" alt="promo 2"></div>
                    <div class="if-promo"><img src="{{ $bannerThird }}" alt="promo 3"></div>
                </div>

                <hr class="if-divider">

                <h2 class="if-title">Os melhores mercados</h2>
                <div class="if-grid-5">
                    @forelse ($features->take(5) as $feature)
                        <div class="if-brand">
                            <div class="dot"></div>
                            <div>
                                <h6>{{ $feature['title'] ?? 'Mercado' }}</h6>
                                <small>{{ \Illuminate\Support\Str::limit(($feature['sub_title'] ?? 'Compras e conveniência'), 24) }}</small>
                            </div>
                        </div>
                    @empty
                        @for ($i=0; $i<3; $i++)
                            <div class="if-brand"><div class="dot"></div><div><h6>Mercado</h6><small>Compras</small></div></div>
                        @endfor
                    @endforelse
                </div>

                <div class="if-cta-grid">
                    <div class="if-cta">
                        <div>
                            <h3>Quer fazer entregas com a gente?</h3>
                            <p>Cadastre-se como entregador e comece a trabalhar no seu ritmo.</p>
                        </div>
                        <a class="btn" href="{{ route('deliveryman.create') }}">Saiba mais</a>
                    </div>
                    <div class="if-cta">
                        <div>
                            <h3>Quer crescer seu restaurante?</h3>
                            <p>Entre para a plataforma e alcance mais clientes todos os dias.</p>
                        </div>
                        <a class="btn" href="{{ route('restaurant.create') }}">Saiba mais</a>
                    </div>
                </div>

                @if ($landing_data['available_zone_status'])
                    <div class="if-zones">
                        @forelse ($zones as $zone)
                            <span class="pill">{{ $zone['display_name'] }}</span>
                        @empty
                            <span class="text-muted">Sem zonas disponíveis.</span>
                        @endforelse
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection

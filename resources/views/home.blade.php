@extends('layouts.landing.app')

@php($business_name = \App\CentralLogics\Helpers::get_business_settings('business_name'))
@section('title', translate('messages.landing_page') . ' | ' . ($business_name != 'null' ? $business_name : 'Sixam Mart'))

@section('content')
    @php($modules = \App\Models\Module::active()->get())
    @php($features = $landing_data['features'] ?? [])
    @php($zones = $landing_data['available_zone_list'] ?? [])

    <style>
        :root {
            --partner-bg: #fff8f3;
            --partner-surface: #ffffff;
            --partner-primary: #ff5a1f;
            --partner-primary-dark: #d9480f;
            --partner-ink: #1f2937;
            --partner-muted: #6b7280;
            --partner-line: #f3d8cb;
        }

        .partner-page {
            background: var(--partner-bg);
            color: var(--partner-ink);
        }

        .partner-nav {
            position: sticky;
            top: 0;
            z-index: 25;
            background: rgba(255, 248, 243, .95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--partner-line);
        }

        .partner-nav-links a {
            color: var(--partner-ink);
            font-weight: 600;
            text-decoration: none;
            margin-left: 1.25rem;
        }

        .partner-btn {
            border-radius: 999px;
            padding: .72rem 1.2rem;
            font-weight: 700;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }

        .partner-btn--primary {
            background: var(--partner-primary);
            color: #fff;
        }

        .partner-btn--primary:hover {
            background: var(--partner-primary-dark);
            color: #fff;
        }

        .partner-btn--ghost {
            background: #fff;
            color: var(--partner-ink);
            border: 1px solid #ffd7c5;
        }

        .partner-btn--xl {
            min-width: 220px;
            padding: 1rem 1.6rem;
            font-size: 1.05rem;
        }

        .partner-hero {
            padding: 4.2rem 0 3rem;
        }

        .partner-badge {
            display: inline-flex;
            border-radius: 999px;
            background: #ffe3d4;
            color: #b93807;
            padding: .38rem .85rem;
            font-size: .85rem;
            font-weight: 700;
        }

        .partner-title {
            font-size: clamp(2rem, 4vw, 3.5rem);
            line-height: 1.07;
            margin: 1rem 0;
            font-weight: 900;
        }

        .partner-subtitle {
            font-size: 1.06rem;
            color: var(--partner-muted);
            max-width: 640px;
        }

        .partner-hero-card,
        .partner-card {
            background: var(--partner-surface);
            border: 1px solid #ffe4d4;
            border-radius: 22px;
            box-shadow: 0 16px 32px rgba(82, 31, 5, .08);
        }

        .partner-hero-card {
            padding: 1.1rem;
        }

        .partner-hero-image {
            width: 100%;
            border-radius: 16px;
            aspect-ratio: 5/4;
            object-fit: cover;
        }

        .partner-section {
            padding: 3.6rem 0;
        }

        .partner-section-title {
            font-size: clamp(1.5rem, 3vw, 2.2rem);
            font-weight: 800;
            margin-bottom: .45rem;
        }

        .partner-section-subtitle {
            color: var(--partner-muted);
            margin-bottom: 1.45rem;
            max-width: 780px;
        }

        .partner-card {
            padding: 1.1rem;
            height: 100%;
            transition: transform .2s ease;
        }

        .partner-card:hover {
            transform: translateY(-4px);
        }

        .partner-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            object-fit: cover;
            margin-bottom: .75rem;
            border: 1px solid #ffe5d8;
        }

        .partner-step {
            border-radius: 18px;
            border: 1px dashed #f8c8ad;
            background: #fff;
            padding: 1rem;
        }

        .partner-step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffdcca;
            color: #9f3309;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .65rem;
        }

        .partner-zone-pill {
            background: #fff;
            border: 1px solid #ffd9c5;
            color: #7b341a;
            border-radius: 999px;
            padding: .42rem .9rem;
            display: inline-flex;
            margin: .25rem;
            font-weight: 600;
        }

        .partner-cta {
            background: linear-gradient(135deg, #1f2937 0%, #111827 70%);
            color: #fff;
            border-radius: 24px;
            padding: 2.1rem;
        }

        .partner-benefits {
            background: #f35d5b;
            border-radius: 28px;
            padding: clamp(1.5rem, 3vw, 2.5rem);
            color: #fff;
        }

        .partner-benefits-title {
            font-size: clamp(1.9rem, 4vw, 3.3rem);
            line-height: 1.1;
            font-weight: 900;
            margin-bottom: 0;
            max-width: 320px;
        }

        .partner-benefit-card {
            background: #fff4f4;
            border-radius: 18px;
            border: 1px solid #ffc7c6;
            padding: 1.25rem;
            color: #374151;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: .8rem;
        }

        .partner-benefit-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #ffd3d2;
            color: #f35d5b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .8rem;
            text-transform: uppercase;
        }

        .partner-benefit-card h3 {
            font-size: clamp(1.3rem, 2.2vw, 2rem);
            color: #ef4444;
            margin: 0;
            line-height: 1.25;
            font-weight: 800;
        }

        .partner-benefit-card p {
            margin: 0;
            color: #4b5563;
            font-size: 1rem;
        }

        .partner-benefit-link {
            margin-top: auto;
            color: #ef4444;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .partner-benefit-link:hover {
            color: #dc2626;
        }

        @media (max-width: 991px) {
            .partner-benefits-title {
                max-width: none;
            }
        }
    </style>

    <main class="partner-page">

        <section id="inicio" class="partner-hero">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="partner-badge">Estrutura inspirada no fluxo de parceiros</span>
                        <h1 class="partner-title">{{ $landing_data['fixed_header_title'] }}</h1>
                        <p class="partner-subtitle">{{ $landing_data['fixed_header_sub_title'] }}</p>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a class="partner-btn partner-btn--primary" href="{{ route('restaurant.create') }}">Quero vender agora</a>
                            <a class="partner-btn partner-btn--ghost" href="{{ route('deliveryman.create') }}">Quero entregar</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="partner-hero-card">
                            <img class="partner-hero-image onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}" src="{{ $landing_data['fixed_header_bg_full_url'] ?? \App\CentralLogics\Helpers::logoFullUrl() }}" alt="Destaque parceiros">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="solucoes" class="partner-section pt-0">
            <div class="container">
                <h2 class="partner-section-title">{{ $landing_data['fixed_module_title'] }}</h2>
                <p class="partner-section-subtitle">{{ $landing_data['fixed_module_sub_title'] }}</p>
                <div class="row g-3">
                    @forelse ($modules as $module)
                        <div class="col-sm-6 col-lg-4">
                            <article class="partner-card">
                                <img class="partner-icon onerror-image" data-onerror-image="{{ asset('public/assets/admin/img/100x100/2.png') }}" src="{{ $module['icon_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $module->module_name }}">
                                <h5 class="fw-bold mb-2">{{ translate("messages.{$module->module_name}") }}</h5>
                                <p class="text-muted mb-0">{!! \Illuminate\Support\Str::limit(strip_tags($module->description ?? ''), 115) !!}</p>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="partner-card text-muted">Nenhum módulo ativo no momento.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="como-funciona" class="partner-section">
            <div class="container">
                <h2 class="partner-section-title">Como funciona para começar</h2>
                <p class="partner-section-subtitle">Fluxo simples para reproduzir a navegação estilo parceiros: cadastro, configuração e operação em poucos passos.</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="partner-step h-100">
                            <span class="partner-step-number">1</span>
                            <h6 class="fw-bold">Crie sua conta</h6>
                            <p class="text-muted mb-0">Cadastre a loja ou entregador e valide dados básicos para ativar sua operação.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="partner-step h-100">
                            <span class="partner-step-number">2</span>
                            <h6 class="fw-bold">Monte seu catálogo</h6>
                            <p class="text-muted mb-0">Use o painel para cadastrar produtos, banners e zonas sem editar código.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="partner-step h-100">
                            <span class="partner-step-number">3</span>
                            <h6 class="fw-bold">Venda com escala</h6>
                            <p class="text-muted mb-0">Acompanhe pedidos, entregas e desempenho com a mesma estrutura de navegação da home.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="recursos" class="partner-section pt-0">
            <div class="container">
                <h2 class="partner-section-title">{{ $landing_data['feature_title'] }}</h2>
                <p class="partner-section-subtitle">{{ $landing_data['feature_short_description'] }}</p>

                <div class="row g-3">
                    @forelse ($features as $feature)
                        <div class="col-md-6 col-lg-4">
                            <article class="partner-card">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img class="partner-icon" src="{{ $feature['image_full_url'] ?? asset('public/assets/admin/img/100x100/2.png') }}" alt="{{ $feature['title'] ?? 'feature' }}">
                                    <h6 class="mb-0 fw-bold">{{ $feature['title'] ?? '' }}</h6>
                                </div>
                                <p class="text-muted mb-0">{{ $feature['sub_title'] ?? '' }}</p>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="partner-card text-muted">Sem recursos configurados no painel ainda.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        @if ($landing_data['available_zone_status'])
            <section id="areas" class="partner-section pt-0">
                <div class="container">
                    <h2 class="partner-section-title">{{ $landing_data['available_zone_title'] }}</h2>
                    <p class="partner-section-subtitle">{{ $landing_data['available_zone_short_description'] }}</p>

                    <div class="partner-card">
                        @forelse ($zones as $zone)
                            <span class="partner-zone-pill">{{ $zone['display_name'] }}</span>
                        @empty
                            <span class="text-muted">Nenhuma zona disponível.</span>
                        @endforelse
                    </div>
                </div>
            </section>
        @endif

        <section class="partner-section pt-0">
            <div class="container">
                <div class="partner-benefits">
                    <div class="row g-3 align-items-stretch">
                        <div class="col-lg-5 d-flex align-items-center">
                            <h2 class="partner-benefits-title">Vantagens que só quem é parceiro Fox Delivery têm</h2>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <article class="partner-benefit-card">
                                <span class="partner-benefit-icon">icon</span>
                                <h3>Gestão simples e fácil</h3>
                                <p>Autonomia e facilidade para gerenciar seus pedidos em uma única plataforma</p>
                                <a class="partner-benefit-link" href="{{ route('restaurant.create') }}">Quero ser parceiro <span aria-hidden="true">›</span></a>
                            </article>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <article class="partner-benefit-card">
                                <span class="partner-benefit-icon">icon</span>
                                <h3>Estamos presentes em 1200 cidades</h3>
                                <p>Seus pedidos com um maior alcance de clientes</p>
                                <a class="partner-benefit-link" href="{{ route('restaurant.create') }}">Quero ser parceiro <span aria-hidden="true">›</span></a>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

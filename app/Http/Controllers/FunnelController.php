<?php

namespace App\Http\Controllers;

use App\Models\DataSetting;
use App\Models\FAQ;
use Illuminate\Http\Response;

class FunnelController extends Controller
{
    private function landingSettings(string $type = 'admin_landing_page'): array
    {
        $datas = DataSetting::with('translations', 'storage')->where('type', $type)->get();
        $settings = [];

        foreach ($datas as $value) {
            $settings[$value->key] = count($value->translations) > 0
                ? $value->translations[0]['value']
                : $value->value;

            $settings[$value->key . '_storage'] = count($value->storage) > 0
                ? $value->storage[0]['value']
                : 'public';
        }

        return $settings;
    }

    private function funnelContent(): array
    {
        $default = [
            'download_app' => [
                'kicker' => 'App do Cliente',
                'title' => 'Peça em minutos: mercado, farmácia, restaurante e mais',
                'subtitle' => 'Instale agora, receba ofertas da sua região e acompanhe seu pedido em tempo real.',
                'highlights' => ['Sem fidelidade', 'Pagamento online e na entrega', 'Suporte local'],
                'benefits' => [
                    ['title' => 'Entrega rápida', 'text' => 'Parceiros perto de você para reduzir tempo de espera.'],
                    ['title' => 'Preço competitivo', 'text' => 'Cupons e campanhas para economizar todo dia.'],
                    ['title' => 'Rastreamento ao vivo', 'text' => 'Acompanhe o pedido do preparo até sua casa.'],
                ],
            ],
            'pwa' => [
                'kicker' => 'Versão Leve',
                'title' => 'Instale rápido no celular, sem loja de apps',
                'subtitle' => 'Ideal para quem quer desempenho e menor consumo de dados.',
                'steps' => [
                    'Abra a plataforma no navegador do celular.',
                    'Toque no menu e escolha Adicionar à tela inicial.',
                    'Confirme e use como se fosse um app.',
                ],
            ],
            'delivery_home' => [
                'kicker' => 'Entregador',
                'title' => 'Ganhe no seu ritmo, com autonomia e suporte',
                'subtitle' => 'Seja bike, moto ou carro e tenha corridas na sua região.',
            ],
            'delivery_start' => [
                'kicker' => 'Cadastro de Entregador',
                'title' => 'Comece em poucos passos e aumente sua renda',
                'steps' => [
                    'Baixe o app do entregador e crie sua conta.',
                    'Preencha seus dados e envie os documentos.',
                    'Após aprovação, ative e comece suas entregas.',
                ],
            ],
            'partner_restaurant' => [
                'kicker' => 'Parceiros',
                'title' => 'Leve seu restaurante para milhões de clientes no Brasil',
                'subtitle' => 'Aumente vendas com delivery e retirada, gestão simples e visibilidade nacional.',
                'steps' => [
                    'Crie sua conta e cadastre CNPJ e dados do titular.',
                    'Escolha plano, revise taxas e assine.',
                    'Configure cardápio e inicie suas vendas.',
                ],
            ],
            'partner_plans' => [
                'kicker' => 'Planos',
                'title' => 'Escolha o plano ideal para o seu momento',
                'plans' => [
                    ['id' => 'starter', 'title' => 'Starter', 'text' => 'Para testar operação e ganhar tração local.'],
                    ['id' => 'growth', 'title' => 'Growth', 'text' => 'Para escalar vendas com previsibilidade.'],
                    ['id' => 'pro', 'title' => 'Pro', 'text' => 'Para operação de alta demanda.'],
                ],
            ],
        ];

        $setting = DataSetting::withoutGlobalScope('translate')
            ->where('type', 'admin_landing_page')
            ->where('key', 'funnel_content_v1')
            ->first();

        $raw = $setting?->getRawOriginal('value');
        if (!$raw) {
            return $default;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return $default;
        }

        return array_replace_recursive($default, $decoded);
    }

    public function downloadApp()
    {
        $settings = $this->landingSettings();
        $links = isset($settings['download_user_app_links']) ? json_decode($settings['download_user_app_links'], true) : [];
        $content = $this->funnelContent()['download_app'];

        return view('funnel.download-app', compact('settings', 'links', 'content'));
    }

    public function pwa()
    {
        $settings = $this->landingSettings();
        $links = isset($settings['fixed_link']) ? json_decode($settings['fixed_link'], true) : [];
        $content = $this->funnelContent()['pwa'];

        return view('funnel.pwa', compact('settings', 'links', 'content'));
    }

    public function pwaManifest(): Response
    {
        $manifest = [
            'name' => 'Plataforma de Entregas',
            'short_name' => 'Entregas',
            'start_url' => '/?source=pwa',
            'scope' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#ea1d2c',
            'description' => 'Peça mercado, farmácia e restaurante com entrega rápida.',
            'icons' => [
                ['src' => asset('public/assets/admin/img/160x160/img2.jpg'), 'sizes' => '160x160', 'type' => 'image/jpeg'],
            ],
        ];

        return response(json_encode($manifest), 200)->header('Content-Type', 'application/manifest+json');
    }

    public function pwaServiceWorker(): Response
    {
        $js = "self.addEventListener('install', e => self.skipWaiting());\n"
            . "self.addEventListener('activate', e => e.waitUntil(self.clients.claim()));\n"
            . "self.addEventListener('fetch', function(){});";

        return response($js, 200)->header('Content-Type', 'application/javascript');
    }

    public function deliveryHome()
    {
        $content = $this->funnelContent()['delivery_home'];
        return view('funnel.delivery-home', compact('content'));
    }

    public function deliveryStart()
    {
        $content = $this->funnelContent()['delivery_start'];
        return view('funnel.delivery-start', compact('content'));
    }

    public function deliveryHelpRegister()
    {
        return view('funnel.delivery-help-register');
    }

    public function deliveryNeedHelp()
    {
        return view('funnel.delivery-need-help');
    }

    public function partnerRestaurant()
    {
        $content = $this->funnelContent()['partner_restaurant'];
        return view('funnel.partner-restaurant', compact('content'));
    }

    public function partnerHelp()
    {
        return view('funnel.partner-help');
    }

    public function partnerFaq()
    {
        $faqs = FAQ::where('status', 1)->latest()->get();

        return view('funnel.partner-faq', compact('faqs'));
    }

    public function partnerPlans()
    {
        $content = $this->funnelContent()['partner_plans'];
        return view('funnel.partner-plans', compact('content'));
    }

    public function partnerPayment()
    {
        return view('funnel.partner-payment');
    }

    public function partnerWelcomeBack()
    {
        return view('funnel.partner-welcome-back');
    }
}

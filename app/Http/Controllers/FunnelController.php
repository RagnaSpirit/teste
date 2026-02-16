<?php

namespace App\Http\Controllers;

use App\Models\DataSetting;
use App\Models\FAQ;

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

    public function downloadApp()
    {
        $settings = $this->landingSettings();
        $links = isset($settings['download_user_app_links']) ? json_decode($settings['download_user_app_links'], true) : [];

        return view('funnel.download-app', compact('settings', 'links'));
    }

    public function pwa()
    {
        $settings = $this->landingSettings();
        $links = isset($settings['fixed_link']) ? json_decode($settings['fixed_link'], true) : [];

        return view('funnel.pwa', compact('settings', 'links'));
    }

    public function deliveryHome()
    {
        return view('funnel.delivery-home');
    }

    public function deliveryStart()
    {
        return view('funnel.delivery-start');
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
        return view('funnel.partner-restaurant');
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
        return view('funnel.partner-plans');
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

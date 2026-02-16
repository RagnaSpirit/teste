<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\DataSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class FunnelLandingController extends Controller
{
    public function index()
    {
        $setting = DataSetting::withoutGlobalScope('translate')
            ->where('type', 'admin_landing_page')
            ->where('key', 'funnel_content_v1')
            ->first();

        $json = $setting?->getRawOriginal('value') ?? '';

        return view('admin-views.business-settings.marketing.funnel-landing', compact('json'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'funnel_content_v1' => 'nullable|string',
        ]);

        $raw = trim((string)$request->funnel_content_v1);
        if ($raw !== '') {
            $decoded = json_decode($raw, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                Toastr::error(translate('Invalid JSON format'));
                return back();
            }
        }

        $setting = DataSetting::withoutGlobalScope('translate')
            ->where('type', 'admin_landing_page')
            ->where('key', 'funnel_content_v1')
            ->first();

        if (!$setting) {
            $setting = new DataSetting();
            $setting->type = 'admin_landing_page';
            $setting->key = 'funnel_content_v1';
        }

        $setting->value = $raw;
        $setting->save();

        Toastr::success(translate('Funnel landing content updated successfully'));
        return back();
    }
}

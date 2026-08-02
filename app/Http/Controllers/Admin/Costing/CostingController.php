<?php

namespace App\Http\Controllers\Admin\Costing;

use App\Http\Controllers\Controller;
use App\Models\Additive;
use App\Models\Combo;
use App\Models\Ingredient;
use App\Models\Item;
use App\Models\PricingConfiguration;
use App\Models\TechnicalSheetIngredient;
use App\Services\Costing\ProductCostingService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CostingController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('costProfile')->latest();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        return view('admin-views.costing.index', [
            'items' => $query->paginate(15),
            'dashboard' => $this->dashboardData(),
        ]);
    }

    public function show(Item $item)
    {
        return view('admin-views.costing.show', [
            'item' => $item->load(['technicalSheetIngredients.ingredient', 'costProfile']),
            'ingredients' => Ingredient::where('active', true)->orderBy('name')->get(),
            'pricing' => PricingConfiguration::latest('id')->first() ?: new PricingConfiguration(),
        ]);
    }

    public function storeIngredient(Request $request, Item $item, ProductCostingService $service)
    {
        $data = $request->validate(['ingredient_id'=>'required|exists:ingredients,id','quantity'=>'required|numeric|min:0.0001','unit'=>'required|string|max:20','loss_percent'=>'nullable|numeric|min:0','yield_percent'=>'nullable|numeric|min:0.0001|max:100']);
        $line = TechnicalSheetIngredient::updateOrCreate(['item_id'=>$item->id,'ingredient_id'=>$data['ingredient_id']], $data + ['item_id'=>$item->id]);
        $service->syncTechnicalIngredient($line);
        return back()->with('success', 'Ficha técnica recalculada.');
    }

    public function pricing(Request $request, ProductCostingService $service)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate(['desired_profit_percent'=>'required|numeric|min:0','tax_percent'=>'required|numeric|min:0','card_fee_percent'=>'required|numeric|min:0','pix_fee_percent'=>'required|numeric|min:0','ifood_fee_percent'=>'required|numeric|min:0','fox_go_fee_percent'=>'required|numeric|min:0','delivery_fee_percent'=>'required|numeric|min:0','marketplace_commission_percent'=>'required|numeric|min:0','fixed_cost_percent'=>'required|numeric|min:0','safety_margin_percent'=>'required|numeric|min:0','packaging_cost'=>'required|numeric|min:0']);
            PricingConfiguration::query()->updateOrCreate(['id' => PricingConfiguration::query()->value('id')], $data);
            Item::has('technicalSheetIngredients')->pluck('id')->each(fn ($id) => $service->recalculateProduct($id));
            return back()->with('success', 'Precificação atualizada e produtos recalculados.');
        }
        return view('admin-views.costing.pricing', ['pricing' => PricingConfiguration::latest('id')->first() ?: new PricingConfiguration()]);
    }

    public function exportCsv(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Produto','CMV','Custo total','Preço balcão','Preço delivery','Preço iFood','Lucro','Margem']);
            Item::with('costProfile')->chunk(100, function ($items) use ($out) {
                foreach ($items as $item) {
                    $p = $item->costProfile?->calculated_prices ?? [];
                    fputcsv($out, [$item->name, $p['cmv_total'] ?? 0, $p['total_cost'] ?? 0, $p['counter_price'] ?? $item->price, $p['delivery_price'] ?? 0, $p['ifood_price'] ?? 0, $p['profit'] ?? 0, $p['margin'] ?? 0]);
                }
            });
            fclose($out);
        }, 'ficha-tecnica-cmv.csv');
    }

    private function dashboardData(): array
    {
        $items = Item::with('costProfile')->get()->map(fn ($item) => ['name'=>$item->name] + ($item->costProfile?->calculated_prices ?? []));
        return ['most_profitable'=>$items->sortByDesc('profit')->take(5),'least_profitable'=>$items->sortBy('profit')->take(5),'highest_cmv'=>$items->sortByDesc('cmv_percent')->take(5),'lowest_cmv'=>$items->sortBy('cmv_percent')->take(5)];
    }
}

<?php

namespace App\Services\Costing;

use App\Models\Combo;
use App\Models\Ingredient;
use App\Models\Item;
use App\Models\PricingConfiguration;
use App\Models\TechnicalSheetIngredient;
use Illuminate\Support\Collection;

class ProductCostingService
{
    public function ingredientCost(float $unitCost, float $quantity, float $lossPercent = 0, float $yieldPercent = 100): float
    {
        $yield = max($yieldPercent, 0.0001) / 100;
        $lossMultiplier = 1 + (max($lossPercent, 0) / 100);
        return round(($unitCost * $quantity * $lossMultiplier) / $yield, 4);
    }

    public function syncTechnicalIngredient(TechnicalSheetIngredient $line): TechnicalSheetIngredient
    {
        $ingredient = $line->ingredient ?: Ingredient::findOrFail($line->ingredient_id);
        $line->unit_cost_snapshot = $ingredient->unitCost();
        $line->loss_percent = $line->loss_percent ?: $ingredient->loss_percent;
        $line->yield_percent = $line->yield_percent ?: $ingredient->yield_percent;
        $line->total_cost = $this->ingredientCost($line->unit_cost_snapshot, $line->quantity, $line->loss_percent, $line->yield_percent);
        $line->save();
        $this->recalculateProduct($line->item_id);
        return $line;
    }

    public function recalculateProduct(int $itemId): array
    {
        $item = Item::with(['technicalSheetIngredients.ingredient', 'costProfile'])->findOrFail($itemId);
        $profile = $item->costProfile()->firstOrCreate(['item_id' => $item->id]);
        $config = PricingConfiguration::query()->latest('id')->first() ?: new PricingConfiguration();
        $ingredientCost = (float) $item->technicalSheetIngredients->sum('total_cost');
        $extras = (float) $profile->packaging_cost + (float) $profile->gas_cost + (float) $profile->energy_cost + (float) $profile->labor_cost + (float) $config->packaging_cost;
        $totalCost = round($ingredientCost + $extras, 4);
        $weight = (float) ($profile->final_weight ?: $item->technicalSheetIngredients->sum('quantity'));
        $prices = $this->generatePrices($totalCost, $config, $profile->manual_price_enabled ? $profile->manual_price : null);
        $prices['cmv_total'] = $ingredientCost;
        $prices['cmv_percent'] = $prices['counter_price'] > 0 ? round(($ingredientCost / $prices['counter_price']) * 100, 4) : 0;
        $prices['total_cost'] = $totalCost;
        $prices['final_weight'] = $weight;
        $profile->forceFill(['final_weight' => $weight, 'calculated_prices' => $prices])->save();
        if (!$profile->manual_price_enabled) {
            $item->price = $prices['counter_price'];
            $item->save();
        }
        return $prices;
    }

    public function generatePrices(float $cost, PricingConfiguration $config, ?float $manualPrice = null): array
    {
        $basePercent = $config->desired_profit_percent + $config->tax_percent + $config->fixed_cost_percent + $config->safety_margin_percent;
        $counter = $manualPrice ?: $this->priceFromPercent($cost, $basePercent);
        $delivery = $this->priceFromPercent($cost, $basePercent + $config->delivery_fee_percent);
        $ifood = $this->priceFromPercent($cost, $basePercent + $config->ifood_fee_percent + $config->marketplace_commission_percent);
        $fox = $this->priceFromPercent($cost, $basePercent + $config->fox_go_fee_percent + $config->marketplace_commission_percent);
        $pix = $this->priceFromPercent($cost, $basePercent + $config->pix_fee_percent);
        $promo = round($counter * 0.9, 2);
        $profit = round($counter - $cost, 4);
        return ['counter_price'=>round($counter,2),'delivery_price'=>round($delivery,2),'ifood_price'=>round($ifood,2),'fox_go_price'=>round($fox,2),'pix_price'=>round($pix,2),'promotion_price'=>$promo,'profit'=>$profit,'margin'=>$counter>0?round(($profit/$counter)*100,4):0,'markup'=>$cost>0?round($counter/$cost,4):0,'contribution_margin'=>$profit];
    }

    public function priceFromPercent(float $cost, float $percent): float
    {
        return round($cost / max(1 - ($percent / 100), 0.0001), 2);
    }

    public function recalculateIngredientDependents(Ingredient $ingredient): int
    {
        return $ingredient->technicalSheets()->with('ingredient')->get()->each(fn ($line) => $this->syncTechnicalIngredient($line))->count();
    }

    public function simulate(Collection $lines, array $increases): array
    {
        $newCost = $lines->sum(fn ($line) => $this->ingredientCost($line->unit_cost_snapshot * (1 + (($increases[$line->ingredient_id] ?? 0) / 100)), $line->quantity, $line->loss_percent, $line->yield_percent));
        return $this->generatePrices(round($newCost, 4), PricingConfiguration::query()->latest('id')->first() ?: new PricingConfiguration());
    }

    public function comboTotals(Combo $combo): array
    {
        $items = $combo->items()->with('item.costProfile')->get();
        $cost = $items->sum(fn ($line) => (($line->item->costProfile->calculated_prices['total_cost'] ?? 0) * $line->quantity));
        $weight = $items->sum(fn ($line) => (($line->item->costProfile->calculated_prices['final_weight'] ?? 0) * $line->quantity));
        $prices = $this->generatePrices($cost, PricingConfiguration::query()->latest('id')->first() ?: new PricingConfiguration());
        $prices['total_cost'] = round($cost, 4); $prices['final_weight'] = round($weight, 4);
        $combo->forceFill(['calculated_prices' => $prices])->save();
        return $prices;
    }
}

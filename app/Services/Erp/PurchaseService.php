<?php

namespace App\Services\Erp;

use App\Models\Erp\Ingredient;
use App\Models\Erp\Purchase;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PurchaseService
{
    public function __construct(private ErpCalculatorService $calculator, private StockService $stock) {}

    public function createAndFinalize(array $data): Purchase
    {
        if (empty($data['items'])) {
            throw new InvalidArgumentException('A compra deve possuir itens.');
        }

        return DB::transaction(function () use ($data) {
            $subtotal = collect($data['items'])->sum(fn ($item) => (float) $item['value']);
            $total = $subtotal + (float) ($data['freight'] ?? 0) + (float) ($data['taxes'] ?? 0) - (float) ($data['discount'] ?? 0);
            if ($total < 0) {
                throw new InvalidArgumentException('Total da compra não pode ser negativo.');
            }

            $purchaseData = collect($data)->except('items')->all();
            $purchase = Purchase::create(array_merge($purchaseData, ['subtotal' => $subtotal, 'total' => $total, 'status' => 'finalized']));

            foreach ($data['items'] as $item) {
                $ingredient = Ingredient::where('company_id', $purchase->company_id)->findOrFail($item['ingredient_id']);
                $entryQty = $this->calculator->baseQuantity((float) $item['quantity'], $ingredient->unit);
                $unitCost = $this->calculator->unitCost((float) $item['value'], (float) $item['quantity'], $ingredient->unit);
                $purchase->items()->create(['ingredient_id' => $ingredient->id, 'quantity' => $entryQty, 'value' => $item['value'], 'unit_cost' => $unitCost]);
                $average = $this->calculator->weightedAverage((float) $ingredient->current_quantity, (float) $ingredient->average_cost, $entryQty, $unitCost);
                $ingredient->forceFill(['average_cost' => $average, 'purchase_price' => $item['value'], 'purchased_quantity' => $entryQty, 'unit_cost' => $unitCost])->save();
                $this->stock->move($ingredient, 'entry', $entryQty, $unitCost, $purchase, ['notes' => 'Entrada automática por compra finalizada']);
            }

            return $purchase->load('items.ingredient', 'supplier');
        });
    }
}

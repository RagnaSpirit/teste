<?php

namespace App\Services\Erp;

use App\Models\Erp\Ingredient;
use App\Models\Erp\StockMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StockService
{
    public function move(Ingredient $ingredient, string $type, float $quantity, ?float $unitCost = null, ?Model $reference = null, array $data = []): StockMovement
    {
        if (! in_array($type, ['entry', 'exit', 'loss', 'adjustment', 'transfer'], true)) {
            throw new InvalidArgumentException('Tipo de movimentação inválido.');
        }
        if ($quantity < 0) {
            throw new InvalidArgumentException('A quantidade não pode ser negativa.');
        }
        if (($unitCost ?? 0) < 0) {
            throw new InvalidArgumentException('O preço não pode ser negativo.');
        }

        return DB::transaction(function () use ($ingredient, $type, $quantity, $unitCost, $reference, $data) {
            $ingredient->refresh();
            $previous = (float) $ingredient->current_quantity;
            $new = match ($type) {
                'entry' => $previous + $quantity,
                'exit', 'loss' => $previous - $quantity,
                'adjustment' => $quantity,
                'transfer' => $previous,
            };

            if ($new < 0) {
                throw new InvalidArgumentException('Estoque negativo não é permitido.');
            }

            $ingredient->forceFill(['current_quantity' => $new])->save();

            return StockMovement::create(array_merge($data, [
                'company_id' => $ingredient->company_id,
                'ingredient_id' => $ingredient->id,
                'type' => $type,
                'quantity' => $quantity,
                'unit_cost' => $unitCost ?? $ingredient->average_cost,
                'previous_quantity' => $previous,
                'new_quantity' => $new,
                'reference_type' => $reference ? $reference::class : Ingredient::class,
                'reference_id' => $reference ? $reference->getKey() : $ingredient->id,
            ]));
        });
    }
}

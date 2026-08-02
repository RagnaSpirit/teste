<?php

namespace App\Services\Erp;

use InvalidArgumentException;

class ErpCalculatorService
{
    public function baseQuantity(float $quantity, string $unit): float
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('A quantidade deve ser maior que zero.');
        }

        return match ($unit) {
            'kg', 'l' => $quantity * 1000,
            'g', 'ml', 'unit' => $quantity,
            default => throw new InvalidArgumentException('Unidade inválida.'),
        };
    }

    public function unitCost(float $price, float $quantity, string $unit): float
    {
        if ($price < 0) {
            throw new InvalidArgumentException('O preço não pode ser negativo.');
        }

        return round($price / $this->baseQuantity($quantity, $unit), 6);
    }

    public function weightedAverage(float $currentQty, float $currentAverage, float $entryQty, float $entryUnitCost): float
    {
        if ($currentQty < 0 || $currentAverage < 0 || $entryQty <= 0 || $entryUnitCost < 0) {
            throw new InvalidArgumentException('Valores inválidos para custo médio.');
        }

        $totalQty = $currentQty + $entryQty;
        return $totalQty == 0.0 ? 0.0 : round((($currentQty * $currentAverage) + ($entryQty * $entryUnitCost)) / $totalQty, 6);
    }
}

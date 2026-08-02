<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Costing\ProductCostingService;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'current_price' => 'float', 'average_price' => 'float', 'package_quantity' => 'float',
        'yield_percent' => 'float', 'loss_percent' => 'float', 'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::updated(function (Ingredient $ingredient) {
            if ($ingredient->wasChanged(['current_price', 'average_price', 'package_quantity', 'yield_percent', 'loss_percent'])) {
                app(ProductCostingService::class)->recalculateIngredientDependents($ingredient);
            }
        });
    }

    public function technicalSheets(): HasMany
    {
        return $this->hasMany(TechnicalSheetIngredient::class);
    }

    public function unitCost(): float
    {
        return $this->package_quantity > 0 ? round($this->current_price / $this->package_quantity, 6) : 0.0;
    }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProductCostProfile extends Model
{
    protected $guarded=['id'];
    protected $casts=['manual_price_enabled'=>'boolean','manual_price'=>'float','delivery_product'=>'boolean','counter_product'=>'boolean','promotion_product'=>'boolean','final_weight'=>'float','packaging_cost'=>'float','gas_cost'=>'float','energy_cost'=>'float','labor_cost'=>'float','calculated_prices'=>'array'];
    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
}

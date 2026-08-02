<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalSheetIngredient extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['quantity'=>'float','loss_percent'=>'float','yield_percent'=>'float','unit_cost_snapshot'=>'float','total_cost'=>'float'];

    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
    public function ingredient(): BelongsTo { return $this->belongsTo(Ingredient::class); }
}

<?php

namespace App\Models\Erp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $table = 'erp_purchase_items';
    protected $guarded = ['id'];

    public function purchase(): BelongsTo { return $this->belongsTo(Purchase::class); }
    public function ingredient(): BelongsTo { return $this->belongsTo(Ingredient::class); }
}

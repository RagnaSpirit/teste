<?php

namespace App\Models\Erp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $table = 'erp_stock_movements';
    protected $guarded = ['id'];

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function ingredient(): BelongsTo { return $this->belongsTo(Ingredient::class); }
    public function reference(): MorphTo { return $this->morphTo(); }
    public function scopeFilter($query, $request)
    {
        return $query
            ->when($request->filled('company_id'), fn ($q) => $q->where('company_id', $request->integer('company_id')))
            ->when($request->filled('ingredient_id'), fn ($q) => $q->where('ingredient_id', $request->integer('ingredient_id')))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')));
    }
}

<?php

namespace App\Models\Erp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $table = 'erp_ingredients';
    protected $guarded = ['id'];
    protected $casts = ['expiration_date' => 'date'];

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function movements(): HasMany { return $this->hasMany(StockMovement::class); }
    public function scopeFilter($query, $request)
    {
        return $query
            ->when($request->filled('company_id') && $this->getTable() !== 'erp_companies', fn ($q) => $q->where('company_id', $request->integer('company_id')))
            ->when($request->filled('status') && $this->getTable() !== 'erp_companies', fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%' . $request->input('search') . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('name', 'like', $term);
                    $sub->orWhere('category', 'like', $term);
                    $sub->orWhere('internal_code', 'like', $term);
                    $sub->orWhere('barcode', 'like', $term);
                    $sub->orWhere('brand', 'like', $term);
                });
            })
            ->when($request->filled('sort_by'), fn ($q) => $q->orderBy($request->input('sort_by'), $request->input('sort_direction', 'asc')));
    }

}

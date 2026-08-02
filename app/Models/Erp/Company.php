<?php

namespace App\Models\Erp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $table = 'erp_companies';
    protected $guarded = ['id'];

    public function suppliers(): HasMany { return $this->hasMany(Supplier::class); }
    public function ingredients(): HasMany { return $this->hasMany(Ingredient::class); }
    public function purchases(): HasMany { return $this->hasMany(Purchase::class); }
    public function scopeFilter($query, $request)
    {
        return $query
            ->when($request->filled('company_id') && $this->getTable() !== 'erp_companies', fn ($q) => $q->where('company_id', $request->integer('company_id')))
            ->when($request->filled('status') && $this->getTable() !== 'erp_companies', fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%' . $request->input('search') . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('trade_name', 'like', $term);
                    $sub->orWhere('legal_name', 'like', $term);
                    $sub->orWhere('cnpj', 'like', $term);
                    $sub->orWhere('city', 'like', $term);
                });
            })
            ->when($request->filled('sort_by'), fn ($q) => $q->orderBy($request->input('sort_by'), $request->input('sort_direction', 'asc')));
    }

}

<?php

namespace App\Http\Controllers\Admin\Erp;

use App\Http\Controllers\Controller;
use App\Models\Erp\Company;
use App\Models\Erp\Ingredient;
use App\Models\Erp\Purchase;
use App\Models\Erp\Supplier;
use Illuminate\Contracts\View\View;

class ErpPageController extends Controller
{
    public function index(): View
    {
        $companies = Company::latest()->paginate(10, ['*'], 'companies_page');
        $suppliers = Supplier::with('purchases')->latest()->paginate(10, ['*'], 'suppliers_page');
        $ingredients = Ingredient::with('supplier')->latest()->paginate(10, ['*'], 'ingredients_page');
        $latestPurchases = Purchase::with('supplier', 'items.ingredient')->latest()->limit(5)->get();
        $stockValue = Ingredient::all()->sum(fn ($ingredient) => (float) $ingredient->current_quantity * (float) $ingredient->average_cost);

        return view('admin-views.erp.index', compact('companies', 'suppliers', 'ingredients', 'latestPurchases', 'stockValue'));
    }
}

<?php

namespace App\Http\Controllers\Api\V1\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Erp\CompanyRequest;
use App\Http\Requests\Erp\IngredientRequest;
use App\Http\Requests\Erp\PurchaseRequest;
use App\Http\Requests\Erp\SupplierRequest;
use App\Models\Erp\Company;
use App\Models\Erp\Ingredient;
use App\Models\Erp\StockMovement;
use App\Models\Erp\Supplier;
use App\Services\Erp\ErpCalculatorService;
use App\Services\Erp\PurchaseService;
use App\Services\Erp\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ErpController extends Controller
{
    public function companies(Request $request): JsonResponse { return response()->json(Company::query()->filter($request)->paginate($request->integer('per_page', 15))); }
    public function storeCompany(CompanyRequest $request): JsonResponse { return response()->json(Company::create($request->validated()), 201); }
    public function updateCompany(CompanyRequest $request, Company $company): JsonResponse { $company->update($request->validated()); return response()->json($company); }

    public function suppliers(Request $request): JsonResponse { return response()->json(Supplier::with('purchases')->filter($request)->paginate($request->integer('per_page', 15))); }
    public function storeSupplier(SupplierRequest $request): JsonResponse { return response()->json(Supplier::create($request->validated()), 201); }
    public function updateSupplier(SupplierRequest $request, Supplier $supplier): JsonResponse { $supplier->update($request->validated()); return response()->json($supplier); }

    public function ingredients(Request $request): JsonResponse { return response()->json(Ingredient::with('supplier')->filter($request)->paginate($request->integer('per_page', 15))); }
    public function storeIngredient(IngredientRequest $request, ErpCalculatorService $calculator): JsonResponse
    {
        $data = $request->validated();
        $data['unit_cost'] = $calculator->unitCost((float) $data['purchase_price'], (float) $data['purchased_quantity'], $data['unit']);
        $data['purchased_quantity'] = $calculator->baseQuantity((float) $data['purchased_quantity'], $data['unit']);
        $data['average_cost'] = $data['unit_cost'];
        return response()->json(Ingredient::create($data), 201);
    }

    public function moveStock(Request $request, StockService $stock): JsonResponse
    {
        $data = $request->validate(['ingredient_id'=>'required|exists:erp_ingredients,id','type'=>'required|in:entry,exit,loss,adjustment,transfer','quantity'=>'required|numeric|min:0','unit_cost'=>'nullable|numeric|min:0','origin_location'=>'nullable|string','destination_location'=>'nullable|string','notes'=>'nullable|string']);
        return response()->json($stock->move(Ingredient::findOrFail($data['ingredient_id']), $data['type'], (float) $data['quantity'], $data['unit_cost'] ?? null, null, $data), 201);
    }

    public function stockMovements(Request $request): JsonResponse { return response()->json(StockMovement::with('ingredient')->filter($request)->latest()->paginate($request->integer('per_page', 15))); }
    public function storePurchase(PurchaseRequest $request, PurchaseService $service): JsonResponse { return response()->json($service->createAndFinalize($request->validated()), 201); }

    public function dashboard(Request $request): JsonResponse
    {
        $companyId = $request->integer('company_id');
        $ingredients = Ingredient::query()->when($companyId, fn ($q) => $q->where('company_id', $companyId));
        return response()->json([
            'ingredients_count' => (clone $ingredients)->count(),
            'stock_value' => round((clone $ingredients)->get()->sum(fn ($i) => (float) $i->current_quantity * (float) $i->average_cost), 2),
            'monthly_purchases' => \App\Models\Erp\Purchase::query()->when($companyId, fn ($q) => $q->where('company_id', $companyId))->whereMonth('purchase_date', now()->month)->whereYear('purchase_date', now()->year)->sum('total'),
            'out_of_stock' => (clone $ingredients)->where('current_quantity', 0)->count(),
            'below_minimum' => (clone $ingredients)->whereColumn('current_quantity', '<', 'minimum_quantity')->count(),
            'latest_purchases' => \App\Models\Erp\Purchase::with('supplier')->when($companyId, fn ($q) => $q->where('company_id', $companyId))->latest()->limit(5)->get(),
        ]);
    }
}

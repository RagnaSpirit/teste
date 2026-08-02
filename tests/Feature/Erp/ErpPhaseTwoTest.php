<?php

namespace Tests\Feature\Erp;

use App\Models\Erp\Company;
use App\Models\Erp\Ingredient;
use App\Models\Erp\Supplier;
use App\Services\Erp\ErpCalculatorService;
use App\Services\Erp\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErpPhaseTwoTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_cost_per_gram_and_milliliter(): void
    {
        $calculator = new ErpCalculatorService();
        $this->assertSame(0.042, $calculator->unitCost(420, 10, 'kg'));
        $this->assertSame(0.016, $calculator->unitCost(80, 5, 'l'));
        $this->assertSame(2.5, $calculator->unitCost(25, 10, 'unit'));
    }

    public function test_purchase_updates_stock_and_average_cost(): void
    {
        $company = Company::create($this->companyData());
        $supplier = Supplier::create($this->supplierData($company->id));
        $ingredient = Ingredient::create($this->ingredientData($company->id, $supplier->id));

        app(PurchaseService::class)->createAndFinalize([
            'company_id' => $company->id,
            'supplier_id' => $supplier->id,
            'purchase_date' => now()->toDateString(),
            'invoice_number' => 'NF-1',
            'freight' => 0,
            'discount' => 0,
            'taxes' => 0,
            'items' => [['ingredient_id' => $ingredient->id, 'quantity' => 10, 'value' => 420]],
        ]);

        $ingredient->refresh();
        $this->assertEquals(20000, (float) $ingredient->current_quantity);
        $this->assertEquals(0.042, (float) $ingredient->average_cost);
        $this->assertDatabaseHas('erp_stock_movements', ['ingredient_id' => $ingredient->id, 'type' => 'entry']);
    }

    private function companyData(): array
    {
        return ['trade_name'=>'Empresa Teste','legal_name'=>'Empresa Teste LTDA','cnpj'=>'11.222.333/0001-81','phone'=>'11999999999','email'=>'empresa@teste.com','zip_code'=>'01000-000','address'=>'Rua A','number'=>'1','city'=>'São Paulo','state'=>'SP','primary_color'=>'#0d6efd','secondary_color'=>'#6c757d','business_hours'=>'08:00-18:00','delivery_fee'=>0,'daily_goal'=>100,'monthly_goal'=>3000];
    }

    private function supplierData(int $companyId): array
    {
        return ['company_id'=>$companyId,'name'=>'Fornecedor','cnpj'=>'45.723.174/0001-10','contact'=>'Maria','phone'=>'11988887777','email'=>'fornecedor@teste.com','address'=>'Rua B','city'=>'São Paulo','state'=>'SP','category'=>'Alimentos','status'=>'active'];
    }

    private function ingredientData(int $companyId, int $supplierId): array
    {
        return ['company_id'=>$companyId,'supplier_id'=>$supplierId,'name'=>'Farinha','category'=>'Secos','internal_code'=>'FAR001','unit'=>'kg','purchase_price'=>420,'purchased_quantity'=>10000,'current_quantity'=>10000,'minimum_quantity'=>1000,'loss_percentage'=>0,'yield_percentage'=>100,'unit_cost'=>0.042,'average_cost'=>0.042,'status'=>'active'];
    }
}

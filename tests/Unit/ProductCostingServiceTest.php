<?php

namespace Tests\Unit;

use App\Models\PricingConfiguration;
use App\Services\Costing\ProductCostingService;
use PHPUnit\Framework\TestCase;

class ProductCostingServiceTest extends TestCase
{
    public function test_it_calculates_ingredient_cost_with_loss_and_yield(): void
    {
        $service = new ProductCostingService();
        $this->assertSame(1.32, $service->ingredientCost(0.008, 150, 10, 100));
    }

    public function test_it_generates_prices_and_margins(): void
    {
        $service = new ProductCostingService();
        $config = new PricingConfiguration(['desired_profit_percent' => 30, 'tax_percent' => 10, 'fixed_cost_percent' => 5, 'safety_margin_percent' => 5, 'delivery_fee_percent' => 10, 'ifood_fee_percent' => 20, 'fox_go_fee_percent' => 12, 'pix_fee_percent' => 1, 'marketplace_commission_percent' => 5]);
        $prices = $service->generatePrices(10, $config);
        $this->assertSame(20.0, $prices['counter_price']);
        $this->assertGreaterThan($prices['counter_price'], $prices['delivery_price']);
        $this->assertGreaterThan($prices['delivery_price'], $prices['ifood_price']);
        $this->assertSame(10.0, $prices['profit']);
        $this->assertSame(50.0, $prices['margin']);
        $this->assertSame(2.0, $prices['markup']);
    }
}

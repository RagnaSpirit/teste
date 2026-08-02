<?php
namespace Tests\Feature\RestaurantOps;
use App\RestaurantOps\Models\{RestaurantIngredient,RestaurantOrder,RestaurantRecipeIngredient,RestaurantTable,RestaurantTab};
use App\RestaurantOps\Services\RestaurantOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantOpsTest extends TestCase
{
    use RefreshDatabase;
    public function test_complete_restaurant_order_flow_decrements_recipe_ingredients_and_prints(): void
    {
        $table = RestaurantTable::create(['name'=>'Mesa 1','status'=>'occupied','capacity'=>4]);
        $tab = RestaurantTab::create(['code'=>'CMD-1','restaurant_table_id'=>$table->id]);
        $ingredient = RestaurantIngredient::create(['name'=>'Blend bovino','unit'=>'g','stock_quantity'=>1000]);
        RestaurantRecipeIngredient::create(['item_id'=>10,'restaurant_ingredient_id'=>$ingredient->id,'quantity'=>180]);
        $service = app(RestaurantOrderService::class);
        $order = $service->createOrder(['type'=>'table','table_id'=>$table->id,'tab_id'=>$tab->id,'items'=>[['item_id'=>10,'name'=>'Burger','quantity'=>2,'unit_price'=>25,'addons'=>[['name'=>'Queijo','price'=>4]],'notes'=>'sem cebola']],'payments'=>[['method'=>'pix','amount'=>58]]]);
        $this->assertSame('received', $order->status);
        $this->assertCount(1, $order->histories);
        $service->changeStatus($order, 'production');
        $finished = $service->changeStatus($order->refresh(), 'finished');
        $this->assertSame('finished', $finished->status);
        $this->assertEquals(640, (float) $ingredient->refresh()->stock_quantity);
        $this->assertCount(3, $finished->histories()->get());
        $this->assertCount(2, $service->createPrintJobs($finished, ['kitchen','beverages']));
    }
    public function test_tables_and_tabs_can_be_managed(): void
    {
        $table = RestaurantTable::create(['name'=>'Balcão 1','status'=>'free']);
        $tab = RestaurantTab::create(['code'=>'C100','restaurant_table_id'=>$table->id]);
        $this->assertSame('free', $table->status);
        $this->assertSame('open', $tab->status);
    }
}

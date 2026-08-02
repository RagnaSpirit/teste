<?php
namespace App\RestaurantOps\Services;
use App\RestaurantOps\Events\RestaurantOrderUpdated;
use App\RestaurantOps\Models\{RestaurantIngredient,RestaurantOrder,RestaurantPrintJob,RestaurantRecipeIngredient};
use App\RestaurantOps\Repositories\RestaurantOrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RestaurantOrderService
{
    public function __construct(private RestaurantOrderRepository $orders) {}
    public function createOrder(array $data): RestaurantOrder
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            $payments = $data['payments'] ?? [];
            $total = collect($items)->sum(fn($i)=> ($i['unit_price'] * $i['quantity']) + collect($i['addons'] ?? [])->sum('price') - ($i['discount'] ?? 0));
            $paid = collect($payments)->sum('amount');
            $order = $this->orders->create(['type'=>$data['type'],'status'=>'received','customer_name'=>$data['customer_name'] ?? null,'table_id'=>$data['table_id'] ?? null,'tab_id'=>$data['tab_id'] ?? null,'coupon_code'=>$data['coupon_code'] ?? null,'items'=>$items,'payments'=>$payments,'subtotal_amount'=>$total,'discount_amount'=>$data['discount_amount'] ?? 0,'total_amount'=>max(0, $total - ($data['discount_amount'] ?? 0)),'change_amount'=>max(0, $paid - max(0, $total - ($data['discount_amount'] ?? 0))),'notes'=>$data['notes'] ?? null,'priority'=>$data['priority'] ?? 0]);
            $order->histories()->create(['from_status'=>null,'to_status'=>'received','actor_type'=>$data['actor_type'] ?? 'system','actor_id'=>$data['actor_id'] ?? null,'notes'=>'Pedido recebido']);
            event(new RestaurantOrderUpdated($order));
            return $order;
        });
    }
    public function changeStatus(RestaurantOrder $order, string $status, array $actor=[]): RestaurantOrder
    {
        if (!in_array($status, RestaurantOrder::STATUSES, true)) throw ValidationException::withMessages(['status'=>'Status inválido.']);
        return DB::transaction(function () use ($order,$status,$actor) {
            $from = $order->status; $order->forceFill(['status'=>$status,'closed_at'=>in_array($status,['finished','cancelled'],true)?now():$order->closed_at])->save();
            $order->histories()->create(['from_status'=>$from,'to_status'=>$status,'actor_type'=>$actor['type'] ?? 'system','actor_id'=>$actor['id'] ?? null,'notes'=>$actor['notes'] ?? null]);
            if ($status === 'finished') $this->decrementIngredients($order);
            event(new RestaurantOrderUpdated($order));
            return $order->refresh();
        });
    }
    private function decrementIngredients(RestaurantOrder $order): void
    {
        foreach ($order->items ?? [] as $item) foreach (RestaurantRecipeIngredient::where('item_id',$item['item_id'])->get() as $recipe) RestaurantIngredient::whereKey($recipe->restaurant_ingredient_id)->decrement('stock_quantity', $recipe->quantity * $item['quantity']);
    }
    public function createPrintJobs(RestaurantOrder $order, array $sectors=['kitchen']): array
    {
        return array_map(fn($sector)=>RestaurantPrintJob::create(['restaurant_order_id'=>$order->id,'sector'=>$sector,'paper_width'=>$sector==='counter'?'58mm':'80mm','payload'=>$order->toArray(),'status'=>'queued'])->id, $sectors);
    }
}

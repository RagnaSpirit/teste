<?php
namespace App\RestaurantOps\Repositories;
use App\RestaurantOps\Models\RestaurantOrder;

class RestaurantOrderRepository
{
    public function create(array $data): RestaurantOrder { return RestaurantOrder::create($data); }
    public function find(int $id): RestaurantOrder { return RestaurantOrder::with(['histories','printJobs'])->findOrFail($id); }
    public function kds(): mixed { return RestaurantOrder::whereIn('status',['received','accepted','production','ready'])->orderBy('priority','desc')->orderBy('created_at')->get(); }
    public function todayMetrics(): array
    {
        $q = RestaurantOrder::whereDate('created_at', today());
        return ['orders_today'=>(clone $q)->count(),'in_production'=>(clone $q)->where('status','production')->count(),'cancelled'=>(clone $q)->where('status','cancelled')->count(),'finished'=>(clone $q)->where('status','finished')->count(),'average_ticket'=>(float)(clone $q)->where('status','finished')->avg('total_amount'),'average_time_minutes'=>(float)(clone $q)->whereNotNull('closed_at')->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, closed_at)) as avg_time')->value('avg_time')];
    }
}

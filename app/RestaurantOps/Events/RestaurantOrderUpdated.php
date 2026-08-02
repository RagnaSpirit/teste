<?php
namespace App\RestaurantOps\Events;
use App\RestaurantOps\Models\RestaurantOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RestaurantOrderUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;
    public function __construct(public RestaurantOrder $order) {}
    public function broadcastOn(): array { return [new Channel('restaurant.orders'), new Channel('restaurant.kds')]; }
    public function broadcastAs(): string { return 'restaurant.order.updated'; }
    public function broadcastWith(): array { return ['order' => $this->order->load('histories')->toArray()]; }
}

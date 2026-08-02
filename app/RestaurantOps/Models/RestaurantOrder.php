<?php

namespace App\RestaurantOps\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantOrder extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['items' => 'array', 'payments' => 'array', 'totals' => 'array', 'closed_at' => 'datetime'];

    public const TYPES = ['counter', 'delivery', 'pickup', 'table', 'takeaway'];
    public const STATUSES = ['received', 'accepted', 'production', 'ready', 'out_for_delivery', 'finished', 'cancelled'];
    public const PAYMENT_METHODS = ['cash', 'pix', 'credit_card', 'debit_card', 'voucher'];

    public function histories(): HasMany { return $this->hasMany(RestaurantOrderStatusHistory::class); }
    public function printJobs(): HasMany { return $this->hasMany(RestaurantPrintJob::class); }
}

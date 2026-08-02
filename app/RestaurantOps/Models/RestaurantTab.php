<?php
namespace App\RestaurantOps\Models;
use Illuminate\Database\Eloquent\Model;
class RestaurantTab extends Model { protected $guarded = ['id']; protected $casts = ['closed_at'=>'datetime']; public const STATUSES=['open','closed','transferred','merged','split']; }

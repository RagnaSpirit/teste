<?php
namespace App\RestaurantOps\Models;
use Illuminate\Database\Eloquent\Model;
class RestaurantTable extends Model { protected $guarded = ['id']; public const STATUSES=['free','occupied','reserved','closing']; }

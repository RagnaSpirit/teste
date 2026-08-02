<?php
namespace App\RestaurantOps\Models;
use Illuminate\Database\Eloquent\Model;
class RestaurantIngredient extends Model { protected $guarded = ['id']; protected $casts=['stock_quantity'=>'decimal:3']; }

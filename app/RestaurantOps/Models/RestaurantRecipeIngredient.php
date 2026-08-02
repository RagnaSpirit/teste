<?php
namespace App\RestaurantOps\Models;
use Illuminate\Database\Eloquent\Model;
class RestaurantRecipeIngredient extends Model { protected $guarded = ['id']; protected $casts=['quantity'=>'decimal:3']; }

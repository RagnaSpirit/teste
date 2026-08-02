<?php
namespace App\RestaurantOps\Models;
use Illuminate\Database\Eloquent\Model;
class RestaurantPermissionProfile extends Model { protected $guarded = ['id']; protected $casts=['permissions'=>'array']; }

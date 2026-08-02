<?php
namespace App\RestaurantOps\Models;
use Illuminate\Database\Eloquent\Model;
class RestaurantPrintJob extends Model { protected $guarded = ['id']; protected $casts=['payload'=>'array','printed_at'=>'datetime']; }

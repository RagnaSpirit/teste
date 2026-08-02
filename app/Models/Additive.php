<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Additive extends Model
{
    protected $guarded=['id']; protected $casts=['quantity'=>'float','cmv'=>'float','profit'=>'float','automatic_price'=>'float','active'=>'boolean'];
    public function ingredient(): BelongsTo { return $this->belongsTo(Ingredient::class); }
}

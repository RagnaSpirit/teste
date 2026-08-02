<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Combo extends Model
{
    protected $guarded=['id']; protected $casts=['active'=>'boolean','calculated_prices'=>'array'];
    public function items(): HasMany { return $this->hasMany(ComboItem::class); }
}

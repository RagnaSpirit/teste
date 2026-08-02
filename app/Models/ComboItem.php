<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ComboItem extends Model
{
    protected $guarded=['id']; protected $casts=['quantity'=>'float'];
    public function combo(): BelongsTo { return $this->belongsTo(Combo::class); }
    public function item(): BelongsTo { return $this->belongsTo(Item::class); }
}

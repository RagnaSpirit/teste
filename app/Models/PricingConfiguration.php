<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PricingConfiguration extends Model
{
    protected $guarded=['id'];
    protected $casts=['desired_profit_percent'=>'float','tax_percent'=>'float','card_fee_percent'=>'float','pix_fee_percent'=>'float','ifood_fee_percent'=>'float','fox_go_fee_percent'=>'float','delivery_fee_percent'=>'float','marketplace_commission_percent'=>'float','fixed_cost_percent'=>'float','safety_margin_percent'=>'float','packaging_cost'=>'float'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Offer extends Model
{
    protected $fillable = [
        'id',
        'product_variation_id',
        'related_offer_product_id',
        'amount',
        'precentges'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['product', 'offer_type'];

    public function product()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    public function getOfferTypeAttribute($value)
    {
        if ($this->related_offer_product_id == $this->product_variation_id) {
            return 'self';
        } else {
            return 'another';
            /* $product = DB::table('product_variations')->select('name', 'id')
             ->whereRaw('id = ' . $this->related_offer_product_id)
             ->first();

             $anotherproduct = DB::table('product_variations')->select('name', 'id')
             ->whereRaw('id = ' . $this->product_variation_id)
             ->first();

             return  'buy ' + $this->amount + $product->name + ' get ' + $this->precentges + '% off on ' + $anotherproduct->name;*/
        }
    }

    public function getProductAttribute($value)
    {
        $products = DB::table('product_variations')->select('name', 'price')
        ->whereRaw('id = ' . $this->product_variation_id)
        ->first();

        /*  $products = ProductVariation::selectRaw('*')
                      ->whereColumn('id', 1)->get();*/

        return $products;
    }
}

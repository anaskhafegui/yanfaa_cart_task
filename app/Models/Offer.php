<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'id',
        'product_id',
        'offer_product_id',
        'precentges'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_offer()
    {
        return $this->belongsTo(Product::class, 'offer_product_id');
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductVariationType extends Model
{
    public function variation(){

        return $this->belongsTo('App\Models\ProductVariation');
    }
}

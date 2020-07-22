<?php



namespace App\Models;
use App\Scoping\Scoper;
use App\Models\ProductVariationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;


class ProductVariation extends Model
{

    public function type()
    {
    	return $this->hasOne(ProductVariationType::class, 'id', 'product_variation_type_id');
    }
    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

 
}

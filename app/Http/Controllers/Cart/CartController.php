<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Requests\Cart\CartUpdateRequest;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function index(Request $request, Cart $cart)
    {
        $cart->sync();

        $request->user()->load([
            'cart.product', 'cart.product.variations.stock', 'cart.stock', 'cart.type'
        ]);

        return (new CartResource($request->user()))
            ->additional([
                'meta' => $this->meta($cart, $this->currencyCheck($request->currencyType))
            ]);
    }

    public function store(CartStoreRequest $request, Cart $cart)
    {
        $cart->add($request->products);
    }

    public function update(ProductVariation $productVariation, CartUpdateRequest $request, Cart $cart)
    {
        $cart->update($productVariation->id, $request->quantity);
    }

    public function destroy(ProductVariation $productVariation, Cart $cart)
    {
        $cart->delete($productVariation->id);
    }

    public function destroyAll(Cart $cart)
    {
        $cart->empty();
    }

    protected function meta(Cart $cart, $currencyType)
    {
        return [
            'empty' => $cart->isEmpty(),
            'subtotal' => $cart->subtotal($currencyType)->formatted(),
            'Taxes' => $cart->getCurrentTaxes($currencyType)->formatted(),
            'total' => $cart->total($currencyType)->formatted(),
            'changed' => $cart->hasChanged()
        ];
    }

    private function currencyCheck($currencyType)
    {
        return  is_string($currencyType) ? $currencyType : 'USD';
    }
}

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
            'cart.product', 'cart.product.variations.stock', 'cart.stock', 'cart.type', 'cart.product.variations.offer'
        ]);

        $userCart = new CartResource($request->user());

        $offers = $this->getCartProductsHasOffer($userCart);

        return ($userCart)
            ->additional([
                'meta' => $this->meta($cart, $this->currencyCheck($request->currencyType), $offers)
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

    protected function meta(Cart $cart, $currencyType, $offers)
    {
        $meta = [
            'empty' => $cart->isEmpty(),
            'subtotal' => $cart->subtotal($currencyType)->formatted(),
            'Taxes' => $cart->getCurrentTaxes($currencyType)->formatted(),
            'total' => $cart->total($currencyType)->formatted(),
            'changed' => $cart->hasChanged(),
        ];
        //where->pivot->quantity;
        if ($offers) {
            $discount = $cart->CalculateOffers($offers, $currencyType);

            $meta['discount'] = $discount;
        }

        return $meta;
    }

    private function checkamountofofferproductexistinCart($offer)
    {
        // dd($offer['offer']['amount']);
        return ($offer->pivot->where('product_variation_id', '=', $offer['offer']['related_offer_product_id'])->first()->quantity >= $offer['offer']['amount']);
    }

    private function getCartProductsHasOffer($userCart)
    {
        $offerProducts = [];
        foreach ($userCart->cart as $key => $offerProduct) {
            if ($userCart->cart[$key]->offer && $this->checkamountofofferproductexistinCart($userCart->cart[$key])) {
                $offerProducts[] = $offerProduct->only('offer');
            }
        }

        return  $this->checkCartProductsHasOffer($offerProducts);
    }

    private function checkCartProductsHasOffer($offerProducts)
    {
        return  empty(!$offerProducts) ? $offerProducts : false;
    }

    private function currencyCheck($currencyType)
    {
        return  is_string($currencyType) ? $currencyType : 'USD';
    }
}

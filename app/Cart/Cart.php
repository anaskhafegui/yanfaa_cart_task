<?php

namespace App\Cart;

use App\Models\User;

class Cart implements CartInterFace
{
    protected $user;

    protected $totalofferPrice = 0;

    protected static $tax = 0.14;

    protected $changed = false;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function add($products)
    {
        $this->user->cart()->syncWithoutDetaching(
            $this->getStorePayload($products)
        );
    }

    public function update($productId, $quantity)
    {
        $this->user->cart()->updateExistingPivot($productId, [
            'quantity' => $quantity
        ]);
    }

    public function delete($productId)
    {
        $this->user->cart()->detach($productId);
    }

    public function empty()
    {
        $this->user->cart()->detach();
    }

    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') === 0;
    }

    public function subtotal($currencyType = 'USD')
    {
        $subtotal = $this->user->cart->sum(function ($product) {
            return $product->price->amount() * $product->pivot->quantity;
        });

        return  $this->currencyConversion($subtotal, $currencyType);
    }

    public function total($currencyType)
    {
        return new Money($this->subtotal($currencyType)->amount() - $this->getCurrentTaxes($currencyType)->amount(), $currencyType);
    }

    public function sync()
    {
        $this->user->cart->each(function ($product) {
            $quantity = $product->minStock($product->pivot->quantity);

            $this->changed = $quantity != $product->pivot->quantity;

            $product->pivot->update([
                'quantity' => $quantity
            ]);
        });
    }

    public function hasChanged()
    {
        return $this->changed;
    }

    protected function getStorePayload($products)
    {
        return collect($products)
            ->keyBy('id')
            ->map(function ($product) {
                return [
                    'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
                ];
            })
            ->toArray();
    }

    protected function getCurrentQuantity($productId)
    {
        if ($product = $this->user->cart->where('id', $productId)->first()) {
            return $product->pivot->quantity;
        }

        return 0;
    }

    public function CalculateOffers($offers, $currencyType)
    {
        $totalofferAmount = '';

        foreach ($offers as  $offer) {
            if ($offer['offer']['offer_type'] == 'self') {
                $discount = $this->currencyConversion($this->getCurrentQuantity($offer['offer']['product_variation_id']) / $offer['offer']['amount'] * $offer['offer']['product']->price * ($offer['offer']['precentges'] / 100), $currencyType);
                $this->totalofferPrice += $discount->amount();
                $totalofferAmount .= $offer['offer']['precentges'] . '%  off ' . $offer['offer']['product']->name . ': -' . $discount->formatted() . '           ';
            } else {
                $discount = $this->currencyConversion((int)min($this->getCurrentQuantity($offer['offer']['related_offer_product_id']) / $offer['offer']['amount'], $this->getCurrentQuantity($offer['offer']['product_variation_id'])) * $offer['offer']['product']->price * $offer['offer']['precentges'] / 100, $currencyType);
                $this->totalofferPrice += $discount->amount();
                $totalofferAmount .= $offer['offer']['precentges'] . '%  off ' . $offer['offer']['product']->name . ': -' . $discount->formatted() . '             ';
            }
        }

        return $totalofferAmount;
    }

    public function getCurrentTaxes($currencyType)
    {
        return  $this->currencyConversion($this->subtotal()->amount() * self::$tax, $currencyType);
    }

    private function currencyConversion($price, $currencyType)
    {
        if ($currencyType == 'EUR') {
            return   new Money(floor($price * 1.5), $currencyType);
        } elseif ($currencyType == 'EGP') {
            return new Money(floor($price * 16), $currencyType);
        } else {
            return   new Money($price);
        }
    }
}

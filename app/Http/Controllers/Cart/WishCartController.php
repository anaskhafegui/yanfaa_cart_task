<?php

namespace App\Http\Controllers\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishCartController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  public function index(Request $request)
  {
      $favourite = Auth::user()->products()->where('user_id', $request->user()->id)->get();

       return response()->json($favourite);
  }

  public function update($product,Wishlist $cart)
  {

    $productid = Product::findorfail($product)->first();

    $cart->add($productid);
  }

  public function destroy($product)
  {
    $productid = Product::findorfail($product)->first();

    Auth::user()->products()->detach($product);
  }

  public function destroyAll(Wishlist $cart)
  {
    $cart->empty();
  }
}

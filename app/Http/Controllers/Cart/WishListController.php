<?php

namespace App\Http\Controllers\Cart;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class WishListController extends Controller
{
		public function __construct()
		{
			$this->middleware(['auth:api']);
		}

    public function index(Request $request)
    {
        $favourite = Auth::user()->products()->where('user_id', $request->user()->id)->get();

         return response()->json($favourite);
    }

    public function toggle($product)
    {

      Auth::user()->products()->toggle($product);

    }


}

<?php

namespace App\Cart;

use App\Cart\Money;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Cart\CartInterFace;

class Wishlist implements CartInterFace
{
	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function add($product)
	{
		$this->user->products()->toggle($product);
	}

	public function delete($productid)
	{

  	$this->user->products()->detach($productid);
	}

	public function empty()
	{
		$this->user->products()->detach();
	}

}

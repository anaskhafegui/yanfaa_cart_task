<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class WishlistEmptyTest extends TestCase
{
    public function test_it_fails_if_the_user_is_unauthenticated()
    {
    	$this->json('DELETE', 'api/wishlist/1')
        	->assertStatus(401);
    }

    public function test_it_fails_if_no_cart_cant_be_found_attached_with_this_user()
    {
    	$user = factory(User::class)->create();

    	$this->jsonAs($user,'DELETE', 'api/wishlist/1')
        	->assertStatus(404);
    }

    public function test_it_deletes_all_the_products_from_the_wishlist_cart()
    {
    	$user = factory(User::class)->create();

    	$user->products()->attach(
    		$product = factory(Product::class)->create());

      $user->products()->attach(
      	$product1 = factory(Product::class)->create());

    	$response = $this->jsonAs($user,'DELETE', "api/destroy-wishlist");

    	$this->assertDatabaseMissing('product_user', [
        'product_id' => $product->id,
        'product_id' => $product1->id,

        ]);
    }


}

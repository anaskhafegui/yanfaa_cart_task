<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class WishlistCartDeleteTest extends TestCase
{
    public function test_it_fails_if_the_user_is_unauthenticated()
    {
    	$this->json('DELETE', 'api/wishlist/1')
        	->assertStatus(401);
    }

    public function test_it_fails_if_the_product_cant_be_found_in_wishlist()
    {
    	$user = factory(User::class)->create();

    	$this->jsonAs($user, 'DELETE', 'api/wishlist/6')
        	->assertStatus(404);
    }

    public function test_it_deletes_the_product_from_the_wishlist_cart()
    {
    	$user = factory(User::class)->create();

    	$user->products()->attach(
    		$product = factory(Product::class)->create());

    	$response = $this->jsonAs($user, 'DELETE', "api/wishlist/{$product->id}");

    	$this->assertDatabaseMissing('product_user', [
            'product_id' => $product->id,
        ]);
    }
}

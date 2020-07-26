<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WishlistCartTest extends TestCase
{
    public function test_it_fails_if_the_user_is_unauthenticated()
    {
      $this->json('POST', 'api/wishlist/1')
         ->assertStatus(401);
    }

    public function test_it_fails_if_the_product_cant_be_found()
    {
    	$user = factory(User::class)->create();

    	$this->jsonAs($user, 'POST', 'api/wishlist/1')
        	->assertStatus(404);
    }

}

<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
use App\Models\Offer;

class OfferTest extends TestCase
{
    public function test_it_belongs_to_a_product()
    {
        $offer = factory(Offer::class)->create();

        $this->assertInstanceOf(ProductVariation::class, $offer->product);
    }
}

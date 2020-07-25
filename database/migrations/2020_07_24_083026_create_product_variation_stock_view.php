<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariationStockView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::statement("CREATE OR REPLACE VIEW product_variation_stock_view AS SELECT product_variations.product_id AS product_id,
                                                                        product_variations.id AS product_variation_id,
                                                                        COALESCE(quantity,0) AS stock,
                                                                        CASE
                                                                            WHEN (quantity > 0) THEN TRUE
                                                                            ELSE FALSE
                                                                        END in_stock
                                                                        FROM product_variations
                                                                        LEFT JOIN(SELECT stocks.product_variation_id AS id,
                                                                        SUM(stocks.quantity) AS quantity FROM stocks GROUP BY stocks.product_variation_id ) AS stocks USING (id)");
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      DB::statement("drop table if exists product_variation_stock_view");


    }
}

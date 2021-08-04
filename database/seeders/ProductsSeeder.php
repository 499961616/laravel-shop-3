<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products  = Product::factory()->count(30)->create();

        foreach ($products as $product){

            $skus = ProductSku::factory()->count(3)->create([
                'product_id' => $product->id
            ]);
            // 找出价格最低的 SKU 价格，把商品价格设置为该价格
            $product->update(['price' => $skus->min('price')]);
        }
    }
}

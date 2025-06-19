<?php namespace Tb\Catalog\Database\Seeders;

use Tb\Catalog\Models\AttributeItem;
use Tb\Catalog\Models\ProductAttribute;
use Winter\Storm\Database\Updates\Seeder;
use Tb\Catalog\Models\Product;

class ProductAttributeSeeder extends Seeder
{
    public function run()
    {
        Product::all()->each(function ($product) {
            $items = AttributeItem::inRandomOrder()->take(rand(2, 4))->get();

            foreach ($items as $item) {
                ProductAttribute::create([
                    'product_id'        => $product->id,
                    'attribute_id'      => $item->attribute_id,
                    'attribute_item_id' => $item->id,
                    'value'             => null,
                ]);
            }
        });
    }
}

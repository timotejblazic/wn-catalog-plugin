<?php namespace Tb\Catalog\Database\Seeders;

use Tb\Catalog\Models\Brand;
use Tb\Catalog\Models\Category;
use Winter\Storm\Database\Updates\Seeder;
use Tb\Catalog\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $brandIds = Brand::pluck('id')->all();
        $catIds = Category::pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            $title = ucfirst($faker->words(3, true));
            $slug = $faker->unique()->slug;
            $product = Product::create([
                'title'          => $title,
                'slug'           => $slug,
                'description'    => $faker->paragraph,
                'base_price'     => $faker->randomFloat(2, 5, 500),
                'brand_id'       => $faker->randomElement($brandIds),
                // optional product‐level discount 20% chance:
                'discount_type'  => $faker->boolean(20) ? $faker->randomElement(['fixed', 'percent']) : null,
                'discount_value' => null,
            ]);

            if ($product->discount_type) {
                $product->discount_value = $product->discount_type === 'fixed'
                    ? $faker->randomFloat(2, 1, min(20, $product->base_price))
                    : $faker->numberBetween(5, 30); // percentage
                $product->save();
            }

            $product->categories()->sync(
                $faker->randomElements($catIds, rand(1, 4))
            );
        }
    }
}

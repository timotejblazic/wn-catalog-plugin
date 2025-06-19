<?php namespace Tb\Catalog\Database\Seeders;

use Winter\Storm\Database\Updates\Seeder;
use Tb\Catalog\Models\Product;
use Faker\Factory as Faker;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        Product::all()->each(function ($product) use ($faker) {
            $extraCount = rand(1, 3);

            for ($j = 0; $j < $extraCount; $j++) {
                $variation = $faker->randomFloat(2, -0.2, 0.2);
                $price = max(0.01, round($product->base_price * (1 + $variation), 2));

                $product->variants()->create([
                    'title' => $product->title . ' ' . strtoupper($faker->bothify('??')),
                    'price' => $price,
                    'stock' => $faker->numberBetween(0, 100),
                ]);
            }
        });
    }
}

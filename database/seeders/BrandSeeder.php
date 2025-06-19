<?php namespace Tb\Catalog\Database\Seeders;

use Winter\Storm\Database\Updates\Seeder;
use Tb\Catalog\Models\Brand;
use Faker\Factory as Faker;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Brand::create([
                'title'       => $faker->company,
                'slug'        => $faker->unique()->slug,
                'description' => $faker->paragraph,
            ]);
        }
    }
}

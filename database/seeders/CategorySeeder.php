<?php namespace Tb\Catalog\Database\Seeders;

use Winter\Storm\Database\Updates\Seeder;
use Tb\Catalog\Models\Category;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Category::create([
                'title'       => ucfirst($faker->unique()->word),
                'slug'        => $faker->unique()->slug,
                'description' => $faker->paragraph,
            ]);
        }
    }
}

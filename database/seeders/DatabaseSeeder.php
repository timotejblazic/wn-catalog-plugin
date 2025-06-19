<?php namespace Tb\Catalog\Database\Seeders;

use Winter\Storm\Database\Updates\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(BrandSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductVariantSeeder::class);
        $this->call(ProductAttributeSeeder::class);
    }
}

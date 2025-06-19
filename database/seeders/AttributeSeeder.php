<?php namespace Tb\Catalog\Database\Seeders;

use Winter\Storm\Database\Updates\Seeder;
use Tb\Catalog\Models\Attribute;
use Faker\Factory as Faker;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $name = ucfirst($faker->unique()->word);
            $code = strtolower(str_replace(' ', '_', $name));

            $attribute = Attribute::create([
                'name' => $name,
                'code' => $code,
            ]);

            $itemCount = rand(3, 5);
            for ($j = 0; $j < $itemCount; $j++) {
                $itemName = $faker->unique()->word;
                $itemCode = $code . '_' . strtolower(str_replace(' ', '_', $itemName));

                $attribute->items()->create([
                    'name' => $itemName,
                    'code' => $itemCode,
                ]);
            }
        }
    }
}

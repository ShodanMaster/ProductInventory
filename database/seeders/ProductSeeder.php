<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Fetching all category IDs to associate products
        $categories = Category::all();

        // Loop to create 10 products
        foreach (range(1, 10) as $index) {
            Product::create([
                'category_id' => $categories->random()->id, // Randomly assigning a category
                'name' => $faker->word, // Random product name
                'sku' => $faker->unique()->word, // Unique SKU
                'price' => $faker->randomFloat(2, 10, 1000), // Random price between 10 and 1000
                'quantity' => $faker->numberBetween(1, 100), // Random quantity between 1 and 100
            ]);
        }
    }
}

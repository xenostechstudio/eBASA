<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    protected array $categories = [
        ['name' => 'Food', 'color' => '#f59e0b'],
        ['name' => 'Beverages', 'color' => '#3b82f6'],
        ['name' => 'Snacks', 'color' => '#10b981'],
        ['name' => 'Desserts', 'color' => '#ec4899'],
        ['name' => 'Sides', 'color' => '#8b5cf6'],
    ];

    public function definition(): array
    {
        $category = $this->faker->randomElement($this->categories);

        return [
            'name' => $category['name'],
            'slug' => Str::slug($category['name']),
            'description' => $this->faker->sentence(),
            'color' => $category['color'],
            'sort_order' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}

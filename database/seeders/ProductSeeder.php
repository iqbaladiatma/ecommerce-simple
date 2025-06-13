<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  public function run()
  {
    $products = [
      [
        'name' => 'Smartphone X',
        'description' => 'Latest smartphone with amazing features',
        'price' => 5000000,
        'stock' => 10,
        'image' => 'products/smartphone.jpg',
        'category' => 'Electronics',
        'status' => 'active',
        'discount' => 0
      ],
      [
        'name' => 'Laptop Pro',
        'description' => 'Powerful laptop for professionals',
        'price' => 15000000,
        'stock' => 5,
        'image' => 'products/laptop.jpg',
        'category' => 'Electronics',
        'status' => 'active',
        'discount' => 10
      ],
      [
        'name' => 'Wireless Headphones',
        'description' => 'High-quality wireless headphones',
        'price' => 1500000,
        'stock' => 20,
        'image' => 'products/headphones.jpg',
        'category' => 'Electronics',
        'status' => 'active',
        'discount' => 5
      ],
      [
        'name' => 'Smart Watch',
        'description' => 'Feature-rich smartwatch',
        'price' => 2000000,
        'stock' => 15,
        'image' => 'products/smartwatch.jpg',
        'category' => 'Electronics',
        'status' => 'active',
        'discount' => 0
      ]
    ];

    foreach ($products as $product) {
      Product::create($product);
    }
  }
}

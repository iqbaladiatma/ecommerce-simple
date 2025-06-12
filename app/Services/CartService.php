<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
  protected $cart;

  public function __construct()
  {
    $this->cart = Session::get('cart', collect());
  }

  public function addToCart(Product $product, int $quantity = 1): void
  {
    $item = $this->cart->firstWhere('id', $product->id);

    if ($item) {
      $item['quantity'] += $quantity;
    } else {
      $this->cart->push([
        'id' => $product->id,
        'name' => $product->name,
        'price' => $product->price,
        'quantity' => $quantity,
        'image' => asset('storage/' . $product->image),
      ]);
    }

    $this->save();
  }

  public function remove(int $productId): void
  {
    $this->cart = $this->cart->filter(fn($item) => $item['id'] !== $productId);
    $this->save();
  }

  public function update(int $productId, int $quantity): void
  {
    $item = $this->cart->firstWhere('id', $productId);

    if ($item) {
      $item['quantity'] = $quantity;
      $this->save();
    }
  }

  public function get(): Collection
  {
    return $this->cart;
  }

  public function clear(): void
  {
    $this->cart = collect();
    $this->save();
  }

  public function total(): float
  {
    return $this->cart->sum(fn($item) => $item['price'] * $item['quantity']);
  }

  public function count(): int
  {
    return $this->cart->sum('quantity');
  }

  protected function save(): void
  {
    Session::put('cart', $this->cart);
  }
}

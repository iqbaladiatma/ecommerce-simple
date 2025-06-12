<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
  public function index()
  {
    $wishlists = auth()->user()->wishlistProducts()->paginate(12);
    return view('frontend.wishlist.index', compact('wishlists'));
  }

  public function toggle(Product $product)
  {
    $user = auth()->user();

    if ($user->wishlistProducts()->where('product_id', $product->id)->exists()) {
      $user->wishlistProducts()->detach($product->id);
      return response()->json(['message' => 'Product removed from wishlist', 'status' => 'removed']);
    }

    $user->wishlistProducts()->attach($product->id);
    return response()->json(['message' => 'Product added to wishlist', 'status' => 'added']);
  }

  public function remove(Product $product)
  {
    auth()->user()->wishlistProducts()->detach($product->id);
    return redirect()->back()->with('success', 'Product removed from wishlist');
  }
}

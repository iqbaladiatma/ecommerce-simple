<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        try {
            // Debug: Check all products
            $allProducts = Product::all();
            Log::info('All products:', [
                'count' => $allProducts->count(),
                'products' => $allProducts->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'status' => $product->status,
                        'category' => $product->category,
                        'stock' => $product->stock
                    ];
                })->toArray()
            ]);

            // Get products without cache
            $query = Product::query()
                ->select(['id', 'name', 'price', 'image', 'category', 'status', 'stock', 'discount'])
                ->where('status', 'active');

            // Apply category filter
            if ($request->has('category') && $request->category !== '') {
                $query->where('category', $request->category);
            }

            // Apply sorting
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }

            // Get paginated results
            $products = $query->paginate(12);

            // Add query string to pagination links
            $products->appends($request->query());

            // Debug: Log the final query and results
            Log::info('Final products query:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'count' => $products->count(),
                'total' => $products->total(),
                'first_page' => $products->items()
            ]);

            return view('frontend.products.index', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error in ProductController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return view('frontend.products.index', ['products' => collect()]);
        }
    }

    public function show(Product $product)
    {
        $cacheKey = 'related_products_' . $product->id;

        $relatedProducts = cache()->remember($cacheKey, now()->addHours(1), function () use ($product) {
            return Product::select(['id', 'name', 'price', 'image', 'category'])
                ->where('category', $product->category)
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->take(4)
                ->get();
        });

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    public function cart()
    {
        $cart = $this->cartService->get();
        $total = $this->cartService->total();

        return view('frontend.cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock available');
        }

        $this->cartService->addToCart($product, $quantity);
        return redirect()->back()->with('success', 'Product added to cart');
    }

    public function remove(Product $product)
    {
        $this->cartService->remove($product->id);
        return redirect()->back()->with('success', 'Product removed from cart');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->quantity;

        if ($quantity > $product->stock) {
            return redirect()->back()->with('error', 'Not enough stock available');
        }

        $this->cartService->update($product->id, $quantity);
        return redirect()->back()->with('success', 'Cart updated');
    }
}

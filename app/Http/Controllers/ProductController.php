<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CurrencyService;

class ProductController extends Controller
{
    /**
     * Tampilan utama produk dengan fitur pencarian, filter, dan konversi kurs.
     */
    public function index(Request $request, CurrencyService $currencyService)
    {
        $currency = $currencyService->getCurrentCurrency();

        $query = Product::with('category')->latest();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(12)->appends($request->only('q', 'category_id'));
        $categories = Category::all();

        $result = $currencyService->getRate($currency);

        $rate = $result['rate'];
        $apiStatus = $result['success'] ? 'success' : 'failed';
        $errorMessage = $result['message'];

        foreach ($products as $product) {
            $product->converted_price = $product->price * $rate;
        }

        return view('products.index', compact(
            'products',
            'categories',
            'currency',
            'rate',
            'apiStatus',
            'errorMessage'
        ));
    }

    /**
     * Menampilkan detail produk dan produk terkait.
     */
    public function show(Product $product, CurrencyService $currencyService)
    {
        $currency = $currencyService->getCurrentCurrency();

        $product->load('category');

        $result = $currencyService->getRate($currency);
        $rate = $result['rate'];

        $product->converted_price = $product->price * $rate;

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->limit(4)
            ->get();

        foreach ($relatedProducts as $relatedProduct) {
            $relatedProduct->converted_price = $relatedProduct->price * $rate;
        }

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'currency'
        ));
    }
}

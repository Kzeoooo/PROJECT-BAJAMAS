<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\CurrencyService;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard yang sesuai berdasarkan mode login (Admin/User).
     * Sudah dilengkapi fitur Search, Dynamic Cart Count, dan Currency Global untuk user dashboard.
     */
    public function index(Request $request, CurrencyService $currencyService): View
    {
        $mode = $request->session()->get('login_as');
        $search = $request->query('q');

        if ($mode === 'admin') {
            // --- DASHBOARD ADMIN ---
            $data = [
                'mode'             => $mode,
                'totalProducts'    => Product::count(),
                'totalCategories'  => Category::count(),
                'totalUsers'       => User::count(),
                'totalOrders'      => Order::count(),
                'recentProducts'   => Product::with('category')->latest()->take(5)->get(),
                'recentOrders'     => Order::with('user')->latest()->take(5)->get(),
            ];

            return view('dashboard', $data);

        } else {
            // --- DASHBOARD USER ---
            $currency = $currencyService->getCurrentCurrency();

            $result = $currencyService->getRate($currency);
            $rate = $result['rate'];
            $apiStatus = $result['success'] ? 'success' : 'failed';
            $errorMessage = $result['message'];

            $categories = Category::select('id', 'name', 'slug')->get();

            $popularProducts = Product::with('category')
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%")
                                 ->orWhere('description', 'like', "%{$search}%");
                })
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            foreach ($popularProducts as $product) {
                $product->converted_price = $product->price * $rate;
            }

            $bannerProduct = Product::inRandomOrder()->first();

            if ($bannerProduct) {
                $bannerProduct->converted_price = $bannerProduct->price * $rate;
            }

            $cart = $request->session()->get('cart', []);
            $cartCount = array_sum($cart);

            return view('dashboard', [
                'mode'            => $mode,
                'categories'      => $categories,
                'popularProducts' => $popularProducts,
                'bannerProduct'   => $bannerProduct,
                'cartCount'       => $cartCount,
                'search'          => $search,
                'currency'        => $currency,
                'rate'            => $rate,
                'apiStatus'       => $apiStatus,
                'errorMessage'    => $errorMessage,
            ]);
        }
    }
}

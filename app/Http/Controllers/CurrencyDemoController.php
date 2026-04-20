<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CurrencyDemoController extends Controller
{
    public function index(Request $request)
    {
        $currency = $request->get('currency', 'IDR');

        $allowedCurrencies = ['IDR', 'USD', 'EUR', 'JPY'];

        if (!in_array($currency, $allowedCurrencies)) {
            $currency = 'IDR';
        }

        $products = Product::all();

        $rate = 1;
        $apiStatus = 'success';
        $errorMessage = null;

        if ($currency !== 'IDR') {
            try {
                $response = Http::withoutVerifying()->timeout(20)->get('https://api.frankfurter.app/latest', [
                    'from' => 'IDR',
                    'to' => $currency,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['rates'][$currency])) {
                        $rate = $data['rates'][$currency];
                    } else {
                        $apiStatus = 'failed';
                        $errorMessage = 'Data kurs tidak ditemukan dari API.';
                    }
                } else {
                    $apiStatus = 'failed';
                    $errorMessage = 'Request API gagal. Status: ' . $response->status();
                }
            } catch (\Exception $e) {
                $apiStatus = 'failed';
                $errorMessage = 'Gagal konek ke API: ' . $e->getMessage();
            }
        }

        foreach ($products as $product) {
            $product->converted_price = $product->price * $rate;
        }

        return view('currency-demo', compact(
            'products',
            'currency',
            'rate',
            'apiStatus',
            'errorMessage'
        ));
    }
}

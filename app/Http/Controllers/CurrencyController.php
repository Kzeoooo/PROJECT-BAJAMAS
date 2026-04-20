<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    public function update(Request $request, CurrencyService $currencyService)
    {
        $request->validate([
            'currency' => 'required|string',
        ]);

        $currency = strtoupper($request->currency);

        if (!in_array($currency, $currencyService->getAllowedCurrencies())) {
            $currency = 'IDR';
        }

        session(['currency' => $currency]);

        return redirect()->back();
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyService
{
    public function getAllowedCurrencies(): array
    {
        return ['IDR', 'USD', 'EUR', 'JPY'];
    }

    public function getCurrentCurrency(): string
    {
        return session('currency', 'IDR');
    }

    public function getRate(string $currency): array
    {
        if ($currency === 'IDR') {
            return [
                'success' => true,
                'rate' => 1,
                'message' => null,
            ];
        }

        try {
            $response = Http::timeout(5)->get('https://api.frankfurter.app/latest', [
                'from' => 'IDR',
                'to' => $currency,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'rate' => $data['rates'][$currency] ?? 1,
                    'message' => null,
                ];
            }
        } catch (\Exception $e) {
            // masuk fallback
        }

        // 🔥 FALLBACK MANUAL (INI YANG PENTING)
        $fallbackRates = [
            'USD' => 0.000064,
            'EUR' => 0.000059,
            'JPY' => 0.0093,
        ];

        return [
            'success' => false,
            'rate' => $fallbackRates[$currency] ?? 1,
            'message' => 'API gagal, menggunakan kurs lokal',
        ];
    }
}

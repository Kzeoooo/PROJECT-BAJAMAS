<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo API Kurs - Bajamas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7f2;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            color: #2f5d3a;
            margin-bottom: 10px;
        }

        .desc {
            color: #555;
            margin-bottom: 20px;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .toolbar form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        select, button {
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            background: #2f8f4f;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.9;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #dff5e5;
            color: #1f6e37;
        }

        .alert-danger {
            background: #ffe1e1;
            color: #922626;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 8px;
            color: #294731;
        }

        .small {
            color: #666;
            font-size: 14px;
        }

        .price {
            margin-top: 12px;
            font-size: 20px;
            font-weight: bold;
            color: #2f8f4f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Demo Public API Kurs - Bajamas</h1>
        <p class="desc">
            Halaman ini menampilkan harga produk dengan konversi kurs menggunakan public API.
        </p>

        <div class="toolbar">
            <form method="GET" action="{{ route('currency.demo') }}">
                <label for="currency">Pilih Mata Uang:</label>
                <select name="currency" id="currency">
                    <option value="IDR" {{ $currency == 'IDR' ? 'selected' : '' }}>IDR</option>
                    <option value="USD" {{ $currency == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ $currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                    <option value="JPY" {{ $currency == 'JPY' ? 'selected' : '' }}>JPY</option>
                </select>
                <button type="submit">Tampilkan</button>
            </form>

            <div>
                <strong>Mata uang aktif:</strong> {{ $currency }}
            </div>
        </div>

        @if($apiStatus === 'failed')
            <div class="alert alert-danger">
                {{ $errorMessage }}
            </div>
        @elseif($currency !== 'IDR')
            <div class="alert alert-success">
                Kurs berhasil diambil. Nilai tukar IDR ke {{ $currency }}: {{ $rate }}
            </div>
        @endif

        <div class="grid">
            @forelse($products as $product)
                <div class="card">
                    <h3>{{ $product->name }}</h3>

                    @if(!empty($product->description))
                        <p>{{ $product->description }}</p>
                    @endif

                    <p class="small">
                        Harga asli: Rp{{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <div class="price">
                        @if($currency === 'IDR')
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        @else
                            {{ $currency }} {{ number_format($product->converted_price, 2) }}
                        @endif
                    </div>
                </div>
            @empty
                <div class="card">
                    <h3>Tidak ada produk</h3>
                    <p class="small">Silakan isi data produk terlebih dahulu.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>

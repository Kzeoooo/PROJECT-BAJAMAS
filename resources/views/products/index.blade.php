@extends('layouts.app')

@section('content')
<div class="py-12 animate-fade-up">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto mb-12">
            @if(isset($categoryName))
                <span class="text-theme-main font-bold tracking-widest uppercase text-sm mb-2 block">Category</span>
                <h1 class="text-4xl md:text-5xl font-black text-theme-dark mb-4">{{ $categoryName }}</h1>
                <p class="text-gray-500">Menampilkan koleksi terbaik dari kategori {{ $categoryName }}</p>
            @else
                <h1 class="text-4xl md:text-5xl font-black text-theme-dark mb-4">Curated Collection 🌿</h1>
                <p class="text-gray-500 text-lg">Temukan kemasan ramah lingkungan berkualitas untuk kebutuhan bisnis Anda.</p>
            @endif
        </div>

        <div class="bg-white p-4 rounded-[2rem] shadow-lg border border-theme-soft mb-12 relative z-20">
            <form method="GET" action="{{ route('products.index') }}" class="flex flex-col md:flex-row gap-4 items-center">

                <div class="relative flex-grow w-full md:w-auto">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari produk apa hari ini?..."
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-theme-main focus:ring-0 rounded-xl transition-all duration-300"
                    >
                </div>

                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </div>
                    <select
                        name="category_id"
                        class="w-full pl-12 pr-8 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-theme-main focus:ring-0 rounded-xl appearance-none cursor-pointer transition-all duration-300"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="w-full md:w-auto px-8 py-3 bg-theme-dark text-white font-bold rounded-xl hover:bg-theme-main transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1">
                    Search
                </button>
            </form>
        </div>

        @if(isset($apiStatus) && $apiStatus === 'failed')
            <div class="mb-8 bg-red-100 border border-red-200 text-red-700 px-6 py-4 rounded-2xl">
                {{ $errorMessage ?? 'Gagal mengambil data kurs dari API.' }}
            </div>
        @elseif(isset($currency) && $currency !== 'IDR')
            <div class="mb-8 bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-2xl">
                Kurs berhasil diambil. Nilai tukar IDR ke {{ $currency }}: {{ $rate }}
            </div>
        @endif

        <div class="flex flex-wrap justify-between items-center gap-3 mb-12">
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('products.index') }}"
                   class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ !request('category_id') && !request()->routeIs('categories.show') ? 'bg-theme-dark text-white shadow-md' : 'bg-white text-gray-500 hover:bg-theme-bg hover:text-theme-dark border border-gray-100' }}">
                    All
                </a>

                @foreach($categories as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}"
                       class="px-5 py-2 rounded-full text-sm font-bold transition-all duration-300 border border-gray-100 {{ request()->url() == route('categories.show', $cat->slug) ? 'bg-theme-dark text-white shadow-md' : 'bg-white text-gray-500 hover:bg-theme-bg hover:text-theme-dark' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <div class="text-sm font-semibold text-theme-dark">
                Mata uang aktif: <span class="text-theme-main">{{ $currency ?? 'IDR' }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div class="group relative bg-white rounded-[2rem] border border-theme-soft overflow-hidden hover:shadow-2xl hover:shadow-theme-main/10 transition-all duration-500 hover:-translate-y-2 flex flex-col h-full">

                    <div class="relative aspect-square overflow-hidden bg-gray-50 p-6 flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700 ease-in-out">
                        @else
                            <div class="flex flex-col items-center justify-center text-gray-300">
                                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-xs">No Image</span>
                            </div>
                        @endif

                        @if($product->category)
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm border border-theme-soft rounded-full text-[10px] font-black uppercase tracking-wider text-theme-dark shadow-sm">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg text-theme-dark mb-2 leading-tight line-clamp-2 group-hover:text-theme-main transition-colors">
                            <a href="{{ route('products.show', $product) }}">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <p class="text-xl font-black text-theme-main mb-6">
                            @if(($currency ?? 'IDR') === 'IDR')
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            @else
                                {{ $currency }} {{ number_format($product->converted_price, 2) }}
                            @endif
                        </p>

                        <div class="mt-auto pt-4 border-t border-gray-100">
                            @auth
                                <form action="{{ route('cart.add') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <input
                                        type="number"
                                        name="qty"
                                        value="1"
                                        min="1"
                                        class="w-14 text-center bg-gray-50 border-transparent rounded-xl focus:bg-white focus:border-theme-main focus:ring-0 font-bold text-gray-600"
                                    >

                                    <button class="flex-1 bg-theme-dark text-white font-bold py-3 rounded-xl hover:bg-theme-main transition-colors flex items-center justify-center gap-2 group/btn">
                                        <span>Add</span>
                                        <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block w-full text-center py-3 border border-theme-soft rounded-xl text-sm font-bold text-gray-500 hover:text-theme-dark hover:border-theme-dark transition-colors">
                                    Login to Buy
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-theme-dark mb-2">Oops! Produk tidak ditemukan.</h3>
                    <p class="text-gray-500">Coba kata kunci lain atau reset filter kategori.</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-6 px-6 py-2 bg-theme-main text-white rounded-lg font-bold hover:bg-theme-dark transition-colors">
                        Reset Filter
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

<style>
    /* Animasi Dasar */
    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    .card-zoom:hover img {
        transform: scale(1.1);
    }

    .card-zoom img {
        transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* Scroll Reveal Engine */
    .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: all 1s cubic-bezier(0.5, 0, 0, 1);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Staggered Delays */
    .delay-100 {
        transition-delay: 0.1s;
    }

    .delay-200 {
        transition-delay: 0.2s;
    }

    .delay-300 {
        transition-delay: 0.3s;
    }
</style>

<div class="space-y-16 pb-12">

    @if(isset($apiStatus) && $apiStatus === 'failed' && ($currency ?? 'IDR') !== 'IDR')
        <div class="mx-4 sm:mx-0 bg-red-100 border border-red-200 text-red-700 px-6 py-4 rounded-2xl">
            {{ $errorMessage ?? 'Gagal mengambil data kurs dari API.' }}
        </div>
    @elseif(isset($currency) && $currency !== 'IDR')
        <div class="mx-4 sm:mx-0 bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-2xl">
            Kurs berhasil diambil. Nilai tukar IDR ke {{ $currency }}: {{ $rate }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-end gap-6 px-4 sm:px-0 reveal active">
        <div>
            <h2 class="text-4xl lg:text-5xl font-black text-theme-dark tracking-tight">
                Hello, {{ Auth::user()->name }}! 🌿
            </h2>
            <p class="text-gray-500 mt-2 text-lg font-medium">Siap berkontribusi untuk bumi hari ini?</p>
            <p class="text-sm font-semibold text-theme-main mt-2">
                Mata uang aktif: {{ $currency ?? 'IDR' }}
            </p>
        </div>

        <div class="w-full md:w-1/2 lg:w-1/3">
            <form action="{{ route('dashboard') }}" method="GET" class="relative group">
                <div
                    class="absolute inset-0 bg-theme-main/20 rounded-2xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cari produk ramah lingkungan..."
                    class="relative w-full px-6 py-4 pl-14 bg-white border border-transparent rounded-2xl focus:border-theme-main focus:ring-4 focus:ring-theme-main/10 outline-none text-base shadow-lg shadow-theme-soft/50 transition-all text-theme-dark font-medium placeholder-gray-400">
                <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none z-10">
                    <svg class="w-6 h-6 text-gray-400 group-focus-within:text-theme-main transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </form>
        </div>
    </div>

    <div class="reveal delay-100">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-theme-dark text-white shadow-2xl group mx-4 sm:mx-0">
            <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
                </svg>
            </div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 items-center">
                <div class="p-10 md:p-16 space-y-6">
                    <span
                        class="inline-block py-2 px-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold uppercase tracking-widest mb-2 shadow-sm">
                        ✨ Recommended Choice
                    </span>

                    @if ($bannerProduct)
                        <h3 class="text-4xl md:text-6xl font-black leading-tight tracking-tighter">
                            {{ $bannerProduct->name }}
                        </h3>
                        <p class="text-gray-300 text-lg max-w-md leading-relaxed">
                            Produk pilihan minggu ini. Dibuat dari material daur ulang berkualitas tinggi.
                        </p>

                        <div class="text-xl font-black text-theme-main">
                            @if(($currency ?? 'IDR') === 'IDR')
                                Rp {{ number_format($bannerProduct->price, 0, ',', '.') }}
                            @else
                                {{ $currency }} {{ number_format($bannerProduct->converted_price, 2) }}
                            @endif
                        </div>

                        <div class="pt-6">
                            <a href="{{ route('products.show', $bannerProduct->slug) }}"
                                class="inline-flex items-center gap-3 bg-white text-theme-dark px-8 py-4 rounded-2xl font-bold hover:bg-theme-main hover:text-white transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-theme-main/50">
                                Lihat Detail
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    @else
                        <h3 class="text-4xl md:text-6xl font-black leading-tight tracking-tighter">
                            Start Your<br>Green Journey.
                        </h3>
                        <p class="text-gray-300 text-lg max-w-md leading-relaxed">
                            Temukan produk terbaik untuk memulai hidup minim sampah.
                        </p>
                        <div class="pt-6">
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center gap-3 bg-white text-theme-dark px-8 py-4 rounded-2xl font-bold hover:bg-theme-main hover:text-white transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-theme-main/50">
                                Mulai Belanja
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

                <div
                    class="relative h-80 md:h-full min-h-[400px] flex items-center justify-center bg-gradient-to-br from-theme-main/20 to-transparent">
                    @if ($bannerProduct && $bannerProduct->image)
                        <img src="{{ asset('storage/' . $bannerProduct->image) }}" alt="Hero Product"
                            class="w-3/4 md:w-2/3 object-contain ...">
                    @else
                        <div
                            class="w-56 h-56 bg-white/10 backdrop-blur-xl border border-white/20 rounded-full flex items-center justify-center animate-float shadow-2xl">
                            <span class="text-8xl filter drop-shadow-lg">🌱</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-0 reveal delay-200">
        <div class="relative bg-theme-main rounded-[3rem] overflow-hidden shadow-2xl text-white p-10 md:p-16">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-black/10 rounded-full blur-3xl -ml-20 -mb-20"></div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 rounded-full text-xs font-bold uppercase tracking-wider backdrop-blur-sm border border-white/30">
                        <span>🌏</span> Our Mission
                    </div>
                    <h3 class="text-3xl md:text-5xl font-black leading-tight tracking-tight">
                        Kenapa <span class="text-theme-dark">Bajamas</span> Tercipta?
                    </h3>
                    <div class="space-y-4 text-white/90 text-lg leading-relaxed font-medium">
                        <p>
                            Bajamas lahir dari kegelisahan melihat gunungan limbah fashion dan plastik yang mencekik
                            bumi kita. Kami percaya, gaya hidup keren tidak harus mengorbankan masa depan planet ini.
                        </p>
                        <p>
                            Setiap produk yang kamu beli di sini adalah langkah kecil untuk mengurangi jejak karbon.
                            Kami menggunakan bahan daur ulang dan proses produksi yang etis.
                        </p>
                    </div>
                    <div class="pt-4">
                        <div class="flex items-center gap-8">
                            <div>
                                <p class="text-3xl font-black text-theme-dark">100%</p>
                                <p class="text-sm uppercase tracking-wider opacity-80">Eco-Friendly</p>
                            </div>
                            <div>
                                <p class="text-3xl font-black text-theme-dark">0%</p>
                                <p class="text-sm uppercase tracking-wider opacity-80">Plastic Waste</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center md:justify-end">
                    <div
                        class="relative w-full max-w-sm aspect-square bg-white/10 backdrop-blur-md rounded-[2.5rem] border border-white/20 flex items-center justify-center p-8 animate-float shadow-xl">
                        <svg class="w-full h-full text-white opacity-90 drop-shadow-lg" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>

                        <div
                            class="absolute -bottom-6 -left-6 bg-white text-theme-main p-4 rounded-2xl shadow-lg animate-bounce duration-[3000ms]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-0 reveal delay-200">
        <div class="flex items-center justify-between mb-8">
            <h4 class="text-2xl font-bold text-theme-dark">Explore Categories</h4>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('categories.show', 'kemasan-makanan') }}"
                class="group relative overflow-hidden rounded-[2.5rem] h-80 border border-theme-soft hover:border-theme-main transition-all duration-500 hover:shadow-xl">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-theme-bg via-white to-theme-bg group-hover:from-theme-main/10 group-hover:via-white group-hover:to-theme-main/5 transition-all duration-500">
                </div>
                <div class="absolute inset-0 flex flex-col items-center justify-center z-10 p-6">
                    <div
                        class="w-24 h-24 bg-white/80 backdrop-blur-xl border border-white/50 rounded-3xl flex items-center justify-center shadow-sm mb-6 group-hover:scale-110 group-hover:shadow-md group-hover:bg-white transition-all duration-500 group-hover:-translate-y-2 text-theme-main">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <h5 class="text-2xl font-black text-theme-dark mb-1 group-hover:text-theme-main transition-colors">
                        Kemasan Makanan
                    </h5>
                    <p
                        class="text-xs font-bold text-gray-400 uppercase tracking-widest opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-500">
                        View Collection &rarr;
                    </p>
                </div>
            </a>

            <a href="{{ route('categories.show', 'kemasan-minuman') }}"
                class="group relative overflow-hidden rounded-[2.5rem] h-80 border border-theme-soft hover:border-theme-main transition-all duration-500 hover:shadow-xl">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-theme-bg via-white to-theme-bg group-hover:from-theme-main/10 group-hover:via-white group-hover:to-theme-main/5 transition-all duration-500">
                </div>
                <div class="absolute inset-0 flex flex-col items-center justify-center z-10 p-6">
                    <div
                        class="w-24 h-24 bg-white/80 backdrop-blur-xl border border-white/50 rounded-3xl flex items-center justify-center shadow-sm mb-6 group-hover:scale-110 group-hover:shadow-md group-hover:bg-white transition-all duration-500 group-hover:-translate-y-2 text-theme-main">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                    <h5 class="text-2xl font-black text-theme-dark mb-1 group-hover:text-theme-main transition-colors">
                        Kemasan Minuman
                    </h5>
                    <p
                        class="text-xs font-bold text-gray-400 uppercase tracking-widest opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-500">
                        View Collection &rarr;
                    </p>
                </div>
            </a>

            <a href="{{ route('categories.show', 'kemasan-pakaian') }}"
                class="group relative overflow-hidden rounded-[2.5rem] h-80 border border-theme-soft hover:border-theme-main transition-all duration-500 hover:shadow-xl">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-theme-bg via-white to-theme-bg group-hover:from-theme-main/10 group-hover:via-white group-hover:to-theme-main/5 transition-all duration-500">
                </div>
                <div class="absolute inset-0 flex flex-col items-center justify-center z-10 p-6">
                    <div
                        class="w-24 h-24 bg-white/80 backdrop-blur-xl border border-white/50 rounded-3xl flex items-center justify-center shadow-sm mb-6 group-hover:scale-110 group-hover:shadow-md group-hover:bg-white transition-all duration-500 group-hover:-translate-y-2 text-theme-main">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h5 class="text-2xl font-black text-theme-dark mb-1 group-hover:text-theme-main transition-colors">
                        Kemasan Pakaian
                    </h5>
                    <p
                        class="text-xs font-bold text-gray-400 uppercase tracking-widest opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-500">
                        View Collection &rarr;
                    </p>
                </div>
            </a>
        </div>
    </div>

    <div class="px-4 sm:px-0 reveal delay-300">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="text-2xl font-bold text-theme-dark">Trending Now 🔥</h4>
                <p class="text-gray-500 text-sm mt-1">Produk paling dicari minggu ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach ($popularProducts as $product)
                <a href="{{ route('products.show', $product->slug) }}"
                    class="card-zoom group bg-white border border-theme-soft rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-theme-main/10 hover:border-theme-main transition-all duration-300 relative flex flex-col h-full transform hover:-translate-y-2">
                    <div class="aspect-square bg-gray-50 relative overflow-hidden p-4 flex items-center justify-center">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-100 rounded-xl">
                                ♻️
                            </div>
                        @endif

                        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span
                                class="w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center text-theme-dark hover:bg-theme-main hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="p-4 flex flex-col flex-grow">
                        <p class="text-[10px] font-black tracking-wider text-theme-main uppercase mb-1">
                            {{ $product->category->name ?? 'Product' }}
                        </p>

                        <h5
                            class="font-bold text-gray-800 text-sm mb-2 line-clamp-2 flex-grow group-hover:text-theme-dark transition-colors">
                            {{ $product->name }}
                        </h5>

                        <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-50">
                            <p class="text-base font-black text-theme-dark">
                                @if(($currency ?? 'IDR') === 'IDR')
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                @else
                                    {{ $currency }} {{ number_format($product->converted_price, 2) }}
                                @endif
                            </p>

                            <div class="flex items-center text-yellow-400 text-xs">
                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                                <span class="text-gray-400 ml-1">5.0</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reveals = document.querySelectorAll('.reveal');

        function revealOnScroll() {
            const windowHeight = window.innerHeight;
            const elementVisible = 100;

            reveals.forEach((reveal) => {
                const elementTop = reveal.getBoundingClientRect().top;
                if (elementTop < windowHeight - elementVisible) {
                    reveal.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', revealOnScroll);
        revealOnScroll();
    });
</script>

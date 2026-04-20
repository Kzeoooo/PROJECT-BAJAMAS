<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-theme-soft sticky top-0 z-50">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-theme-dark" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-theme-dark hover:text-theme-main active:text-theme-main font-medium text-base">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')" class="text-theme-dark hover:text-theme-main active:text-theme-main font-medium text-base">
                            {{ __('Manage Product') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="text-theme-dark hover:text-theme-main active:text-theme-main font-medium text-base">
                            {{ __('Manage Orders') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" class="text-theme-dark hover:text-theme-main active:text-theme-main font-medium text-base">
                            {{ __('Manage Categories') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="text-theme-dark hover:text-theme-main active:text-theme-main font-medium text-base">
                            {{ __('Manage Users') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-theme-dark hover:text-theme-main active:text-theme-main font-medium text-base">
                            {{ __('Shop') }}
                        </x-nav-link>
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-theme-dark hover:text-theme-main active:text-theme-main font-medium text-base">
                            {{ __('Category') }}
                        </x-nav-link>
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="text-theme-dark hover:text-theme-main active:text-theme-main font-medium text-base">
                            {{ __('My Orders') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                <form method="POST" action="{{ route('currency.update') }}" class="flex items-center gap-2">
                    @csrf
                    <select name="currency"
                        onchange="this.form.submit()"
                        class="px-3 py-2 rounded-xl border border-theme-soft bg-white text-sm font-semibold text-theme-dark focus:border-theme-main focus:ring-0">

                        @foreach($allowedCurrencies as $curr)
                            <option value="{{ $curr }}" {{ $currentCurrency === $curr ? 'selected' : '' }}>
                                {{ $curr }}
                            </option>
                        @endforeach

                    </select>
                </form>

                @if(Auth::user()->role !== 'admin')
                    <a href="{{ route('cart.index') }}" class="me-2 inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-theme-dark bg-transparent hover:bg-theme-bg hover:text-theme-main transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Cart</span>
                        <span class="ms-2 bg-theme-dark text-white rounded-full px-2 py-0.5 text-xs">
                            {{ array_sum(session('cart', [])) ?: 0 }}
                        </span>
                    </a>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-theme-dark bg-transparent hover:text-theme-main focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if(Auth::user()->role === 'admin')
                            <x-dropdown-link :href="route('admin.products.index')">
                                {{ __('Manage Product') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.orders.index')">
                                {{ __('Manage Orders') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.categories.index')">
                                {{ __('Manage Categories') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.users.index')">
                                {{ __('Manage Users') }}
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('orders.index')">
                                {{ __('My Orders') }}
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-red-500 font-bold">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @else
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <form method="POST" action="{{ route('currency.update') }}" class="flex items-center gap-2">
                    @csrf
                    <select
                        name="currency"
                        onchange="this.form.submit()"
                        class="px-3 py-2 rounded-xl border border-theme-soft bg-white text-sm font-semibold text-theme-dark focus:border-theme-main focus:ring-0"
                    >
                        @foreach($allowedCurrencies as $curr)
                            <option value="{{ $curr }}" {{ $currentCurrency === $curr ? 'selected' : '' }}>
                                {{ $curr }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <form method="GET" action="{{ route('products.index') }}" class="me-4 flex items-center">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..."
                        class="w-48 px-3 py-2 text-sm border border-theme-soft rounded-l-lg focus:ring-theme-main focus:border-theme-main bg-white">
                    <button type="submit" class="px-3 py-2 bg-theme-main text-white rounded-r-lg hover:bg-theme-dark transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
                <a href="{{ route('login') }}" class="text-sm font-medium text-theme-dark hover:text-theme-main transition-colors">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-theme-main rounded-lg hover:bg-theme-dark transition-colors">Register</a>
            </div>
            @endauth

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-theme-dark hover:text-theme-main hover:bg-theme-bg focus:outline-none focus:bg-theme-bg transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-theme-soft">
        <div class="px-4 py-3 border-b border-theme-soft">
            <form method="POST" action="{{ route('currency.update') }}" class="flex items-center gap-2">
                @csrf
                <label for="currency-mobile" class="text-sm font-semibold text-theme-dark whitespace-nowrap">
                    Currency
                </label>
                <select
                    name="currency"
                    id="currency-mobile"
                    onchange="this.form.submit()"
                    class="flex-1 px-3 py-2 rounded-xl border border-theme-soft bg-white text-sm font-semibold text-theme-dark focus:border-theme-main focus:ring-0"
                >
                    @foreach($allowedCurrencies as $curr)
                        <option value="{{ $curr }}" {{ $currentCurrency === $curr ? 'selected' : '' }}>
                            {{ $curr }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-theme-dark border-theme-main bg-theme-bg/50">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        @auth
        <div class="pt-4 pb-1 border-t border-theme-soft">
            <div class="px-4">
                <div class="font-medium text-base text-theme-dark">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        {{ __('Manage Product') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                        {{ __('Manage Orders') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        {{ __('Manage Categories') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Manage Users') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                        {{ __('Category') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                        {{ __('My Orders') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                    this.closest('form').submit();" class="text-red-500 font-bold">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-4 pb-1 border-t border-theme-soft">
             <x-responsive-nav-link :href="route('login')">Login</x-responsive-nav-link>
             <x-responsive-nav-link :href="route('register')">Register</x-responsive-nav-link>
        </div>
        @endauth
    </div>
</nav>

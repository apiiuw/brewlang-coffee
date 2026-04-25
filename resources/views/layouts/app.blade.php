<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Brewlang Coffee</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-950 text-stone-50 flex flex-col min-h-screen selection:bg-amber-400/30 selection:text-amber-300">

    {{-- Navbar --}}
    <nav class="glass-nav fixed start-0 top-0 z-50 w-full">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-8">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center transition group-hover:bg-amber-400/20">
                    <i class="fa-solid fa-mug-hot text-amber-400 text-sm"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-stone-50">Brewlang Coffee</span>
            </a>

            {{-- Right: cart + auth + mobile toggle --}}
            <div class="flex items-center gap-3 md:order-2">

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="relative p-2 text-stone-400 hover:text-amber-400 transition" aria-label="Cart">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                    @if(($cartItemCount ?? 0) > 0)
                        <span id="cart-badge" class="absolute -right-0.5 -top-0.5 inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-amber-400 px-1 text-xs font-bold text-stone-950">
                            {{ $cartItemCount }}
                        </span>
                    @endif
                </a>

                @auth
                    {{-- User Dropdown --}}
                    <button id="dropdownUserAvatarButton" data-dropdown-toggle="dropdownAvatar"
                        class="flex items-center gap-2 rounded-xl bg-stone-800 hover:bg-stone-700 border border-stone-700 px-3 py-1.5 transition" type="button">
                        <div class="w-7 h-7 rounded-lg bg-amber-400/10 border border-amber-400/20 flex items-center justify-center text-amber-400 font-bold text-xs uppercase">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <i class="fa-solid fa-chevron-down text-stone-500 text-xs"></i>
                    </button>

                    {{-- Dropdown --}}
                    <div id="dropdownAvatar" class="z-50 hidden w-52 rounded-2xl border border-stone-700 bg-stone-900 shadow-2xl dark-glass">
                        <div class="px-4 py-3 border-b border-stone-700/50">
                            <div class="font-bold text-stone-100 text-sm truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs font-medium text-amber-400/70 uppercase tracking-widest mt-0.5 truncate">{{ auth()->user()->role }}</div>
                        </div>
                        <ul class="py-2 text-sm" aria-labelledby="dropdownUserAvatarButton">
                            @if(auth()->user()->role === 'owner')
                            <li>
                                <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-stone-300 hover:text-amber-400 hover:bg-stone-800/50 transition">
                                    <i class="fa-solid fa-gauge-high w-4 text-center text-xs"></i>
                                    Dashboard
                                </a>
                            </li>
                            @elseif(auth()->user()->role === 'staff')
                            <li>
                                <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-stone-300 hover:text-amber-400 hover:bg-stone-800/50 transition">
                                    <i class="fa-solid fa-gauge-high w-4 text-center text-xs"></i>
                                    Dashboard
                                </a>
                            </li>
                            @endif
                        </ul>
                        <div class="py-2 border-t border-stone-700/50">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-red-400 hover:bg-red-400/10 transition">
                                    <i class="fa-solid fa-right-from-bracket w-4 text-center text-xs"></i>
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-primary !py-2 !px-3 !text-xs !rounded-xl glow-amber sm:!px-4 sm:!text-sm">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span class="hidden sm:inline">Login</span>
                    </a>
                @endauth

                {{-- Mobile menu --}}
                <details class="menu-toggle relative md:hidden">
                    <summary class="inline-flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-lg text-stone-400 transition hover:bg-stone-800 hover:text-amber-400 [&::-webkit-details-marker]:hidden">
                        <span class="sr-only">Open menu</span>
                        <i class="menu-toggle-closed fa-solid fa-bars text-sm"></i>
                        <i class="menu-toggle-open fa-solid fa-xmark text-sm"></i>
                    </summary>
                    <div class="absolute right-0 mt-3 w-[min(18rem,calc(100vw-2rem))] rounded-2xl border border-stone-800 bg-stone-900 p-3 shadow-2xl">
                        <a href="{{ route('menu') }}" class="flex items-center gap-2 rounded-xl px-3 py-3 text-sm font-semibold text-stone-300 transition hover:bg-stone-800 hover:text-amber-400">
                            <i class="fa-solid fa-utensils w-4 text-xs text-amber-400/50"></i>
                            Menu
                        </a>
                        <a href="{{ route('about') }}" class="flex items-center gap-2 rounded-xl px-3 py-3 text-sm font-semibold text-stone-300 transition hover:bg-stone-800 hover:text-amber-400">
                            <i class="fa-solid fa-circle-info w-4 text-xs text-amber-400/50"></i>
                            About
                        </a>
                        <a href="{{ route('contact') }}" class="flex items-center gap-2 rounded-xl px-3 py-3 text-sm font-semibold text-stone-300 transition hover:bg-stone-800 hover:text-amber-400">
                            <i class="fa-solid fa-envelope w-4 text-xs text-amber-400/50"></i>
                            Contact
                        </a>
                    </div>
                </details>
            </div>

            {{-- Nav Links --}}
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-mobile">
                <ul class="mt-3 flex flex-col rounded-2xl border border-stone-800 bg-stone-900 p-3 md:mt-0 md:flex-row md:gap-1 md:border-0 md:bg-transparent md:p-0">
                    <li>
                        <a href="{{ route('menu') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-stone-300 transition hover:bg-stone-800 hover:text-amber-400 md:hover:bg-transparent">
                            <i class="fa-solid fa-utensils text-xs text-amber-400/50"></i>
                            Menu
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-stone-300 transition hover:bg-stone-800 hover:text-amber-400 md:hover:bg-transparent">
                            <i class="fa-solid fa-circle-info text-xs text-amber-400/50"></i>
                            About
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-stone-300 transition hover:bg-stone-800 hover:text-amber-400 md:hover:bg-transparent">
                            <i class="fa-solid fa-envelope text-xs text-amber-400/50"></i>
                            Contact
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow w-full pt-16">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-auto border-t border-stone-800 bg-stone-900">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-5 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-lg bg-amber-400/10 border border-amber-400/20 flex items-center justify-center transition group-hover:bg-amber-400/20">
                        <i class="fa-solid fa-mug-hot text-amber-400 text-xs"></i>
                    </div>
                    <span class="font-bold text-stone-300 tracking-tight">Brewlang</span>
                </a>
                <ul class="flex flex-wrap justify-center gap-x-5 gap-y-2 text-sm font-medium text-stone-500">
                    <li><a href="{{ route('about') }}" class="hover:text-amber-400 transition">About</a></li>
                    <li><a href="{{ route('menu') }}" class="hover:text-amber-400 transition">Menu</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-amber-400 transition">Contact</a></li>
                </ul>
            </div>
            <div class="mt-8 border-t border-stone-800 pt-6">
                <p class="text-sm text-stone-600 text-center">&copy; {{ date('Y') }} <a href="{{ route('home') }}" class="hover:text-amber-400 transition">Brewlang Coffee</a>. Crafted with care.</p>
            </div>
        </div>
    </footer>

</body>
</html>

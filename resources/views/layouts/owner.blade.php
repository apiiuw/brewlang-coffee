<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Owner Dashboard — Brewlang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-950 text-stone-50">
    <div class="lg:hidden">
        <header class="sticky top-0 z-40 border-b border-stone-800 bg-stone-900/95 px-4 py-3 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-amber-400/20 bg-amber-400/10">
                        <i class="fa-solid fa-mug-hot text-sm text-amber-400"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-xs font-semibold uppercase tracking-[0.2em] text-amber-400/70">Brewlang</p>
                        <h1 class="truncate text-sm font-bold text-stone-200">Owner Panel</h1>
                    </div>
                </div>
                <details class="menu-toggle relative">
                    <summary class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-stone-700 bg-stone-800 text-stone-300 transition hover:border-amber-400/40 hover:text-amber-400 [&::-webkit-details-marker]:hidden">
                        <i class="menu-toggle-closed fa-solid fa-bars text-sm"></i>
                        <i class="menu-toggle-open fa-solid fa-xmark text-sm"></i>
                    </summary>
                    <div class="absolute right-0 mt-3 w-[min(18rem,calc(100vw-2rem))] rounded-2xl border border-stone-800 bg-stone-900 p-3 shadow-2xl">
                        <nav class="space-y-1">
                            <a href="{{ route('owner.dashboard') }}" class="sidebar-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}"> <i class="fa-solid fa-gauge-high w-5 text-center text-sm"></i> Dashboard </a>
                            <a href="{{ route('owner.menus.index') }}" class="sidebar-link {{ request()->routeIs('owner.menus.*') ? 'active' : '' }}"> <i class="fa-solid fa-utensils w-5 text-center text-sm"></i> Menu Management </a>
                            <a href="{{ route('owner.orders.index') }}" class="sidebar-link {{ request()->routeIs('owner.orders.*') ? 'active' : '' }}"> <i class="fa-solid fa-receipt w-5 text-center text-sm"></i> Orders </a>
                            <a href="{{ route('owner.expenses.index') }}" class="sidebar-link {{ request()->routeIs('owner.expenses.*') ? 'active' : '' }}"> <i class="fa-solid fa-wallet w-5 text-center text-sm"></i> Expenses </a>
                            <a href="{{ route('owner.staff.index') }}" class="sidebar-link {{ request()->routeIs('owner.staff.*') ? 'active' : '' }}"> <i class="fa-solid fa-users w-5 text-center text-sm"></i> Staff Accounts </a>
                            <a href="{{ route('owner.reports.index') }}" class="sidebar-link {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}"> <i class="fa-solid fa-chart-bar w-5 text-center text-sm"></i> Reports </a>
                        </nav>
                        <div class="mt-3 border-t border-stone-800 pt-3">
                            <div class="mb-3 flex items-center gap-3">
                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl border border-amber-400/20 bg-amber-400/10 text-sm font-bold uppercase text-amber-400">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-stone-200">{{ auth()->user()->name }}</p>
                                    <p class="text-xs uppercase tracking-widest text-amber-400/60">Owner</p>
                                </div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-stone-700 px-3 py-2 text-sm font-semibold text-stone-400 transition hover:border-red-400/40 hover:bg-red-400/5 hover:text-red-400">
                                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </details>
            </div>
        </header>
    </div>

    <div class="hidden min-h-screen lg:flex lg:h-screen lg:flex-row">

        {{-- Sidebar --}}
        <aside class="flex w-64 flex-shrink-0 flex-col border-r border-stone-800 bg-stone-900 lg:sticky lg:top-0 lg:h-screen">

            {{-- Sidebar Header --}}
            <div class="border-b border-stone-800 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-amber-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-400/70">Brewlang</p>
                        <h1 class="text-sm font-bold text-stone-200">Owner Panel</h1>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto p-3 space-y-1">
                <a href="{{ route('owner.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm"></i>
                    Dashboard
                </a>
                <a href="{{ route('owner.menus.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.menus.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-utensils w-5 text-center text-sm"></i>
                    Menu Management
                </a>
                <a href="{{ route('owner.orders.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.orders.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt w-5 text-center text-sm"></i>
                    Orders
                </a>
                <a href="{{ route('owner.expenses.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.expenses.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-wallet w-5 text-center text-sm"></i>
                    Expenses
                </a>
                <a href="{{ route('owner.staff.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.staff.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users w-5 text-center text-sm"></i>
                    Staff Accounts
                </a>
                <a href="{{ route('owner.reports.index') }}"
                   class="sidebar-link {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-bar w-5 text-center text-sm"></i>
                    Reports
                </a>
            </nav>

            {{-- User & Logout --}}
            <div class="border-t border-stone-800 p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center text-amber-400 font-bold text-sm uppercase flex-shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-stone-200 text-sm truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-amber-400/60 uppercase tracking-widest">Owner</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl border border-stone-700 px-3 py-2 text-sm font-semibold text-stone-400 hover:text-red-400 hover:border-red-400/40 hover:bg-red-400/5 transition">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="w-full flex-grow bg-stone-950 p-6 lg:h-screen lg:overflow-y-auto lg:p-8">
            @yield('content')
        </main>

    </div>

    <main class="w-full bg-stone-950 p-4 sm:p-6 lg:hidden">
        @yield('content')
    </main>
</body>
</html>

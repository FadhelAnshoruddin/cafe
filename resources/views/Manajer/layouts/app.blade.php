<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Cafe Senja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#121010] text-[#EDE6E1] font-[Inter,Arial,sans-serif] antialiased">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-[248px] bg-[#0B0909] flex flex-col">

            {{-- Logo --}}

            <div class="flex items-center gap-3 px-[26px] py-[30px]">
                <div class="w-9 h-9 rounded-md bg-[#EDE6E1] flex items-center justify-center shrink-0 overflow-hidden">
                    <img src="{{ asset('asset/logo-coffe-senja.png') }}" alt="Logo" class="w-full h-full object-cover" />
                </div>
                <div>
                    <p class="text-[15px] font-semibold text-[#EDE6E1] leading-tight">Coffe Senja</p>
                    <p class="text-[12px] text-[#8F8480] leading-tight">Manajer</p>
                </div>
            </div>

            {{-- Navigasi --}}

            <nav class="flex-1 px-4 space-y-1">
                @php
                    $navItems = [
                        ['route' => 'manajer.dashboard', 'label' => 'Laporan', 'icon' => 'chart'],
                        ['route' => 'manajer.menu', 'label' => 'Menu', 'icon' => 'menu'],
                        ['route' => 'manajer.resep', 'label' => 'Resep', 'icon' => 'resep'],
                        ['route' => 'manajer.bahan', 'label' => 'Bahan', 'icon' => 'bahan'],
                        ['route' => 'manajer.stok-masuk', 'label' => 'Stok masuk', 'icon' => 'stok'],
                    ];
                    $currentRoute = Route::currentRouteName();
                @endphp

                @foreach($navItems as $item)
                    @php
                        $isActive = $currentRoute === $item['route'];
                        $routeExists = Route::has($item['route']);
                    @endphp
                    <a
                        href="{{ $routeExists ? route($item['route']) : '#' }}"
                        class="flex items-center gap-3 px-3 h-10 rounded-md text-[14px] font-medium transition-colors duration-150
                            {{ $isActive
                                ? 'bg-[#3A2A23] text-[#EDE6E1]'
                                : 'text-[#B5A9A3] hover:bg-[#1C1716] hover:text-[#EDE6E1]' }}"
                    >
                        {{-- Icon SVG inline --}}

                        @switch($item['icon'])
                            @case('chart')
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13h4v8H3zm7-5h4v13h-4zm7-5h4v18h-4z"/></svg>
                                @break
                            @case('menu')
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                @break
                            @case('resep')
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.331 0 4.466.89 6.042 2.355M12 6.042A8.968 8.968 0 0118 3.75c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.331 0-4.466.89-6.042 2.355M12 6.042V20.355"/></svg>
                                @break
                            @case('bahan')
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                @break
                            @case('stok')
                                <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                @break
                        @endswitch

                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Footer sidebar --}}

            <div class="px-4 py-6 border-t border-[#1C1716]">
                <div class="flex items-center gap-3 text-[13px] text-[#8F8480]">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-[#D86A63] transition-colors cursor-pointer">Keluar</button>
                    </form>
                    <span class="text-[#322927]">·</span>
                    <a href="#" class="hover:text-[#EDE6E1] transition-colors">Ganti password</a>
                </div>
            </div>
        </aside>

        {{-- KONTEN UTAMA --}}

        <main class="flex-1 ml-[248px] p-8">
            @yield('content')
        </main>

    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') — Cafe Senja</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#121010] text-[#EDE6E1] font-[Inter,Arial,sans-serif] antialiased overflow-x-hidden">

<div class="min-h-screen md:flex">

    {{-- SIDEBAR --}}
    <aside class="relative w-full bg-[#0D0B0A] border-b border-[#29211F] flex flex-col md:fixed md:inset-y-0 md:left-0 md:z-40 md:w-[248px] md:border-b-0 md:border-r md:border-[#29211F]">

        {{-- LOGO --}}
        <div class="flex items-center gap-3 px-5 py-5 md:px-6 md:py-7">

            <div class="w-10 h-10 rounded-lg bg-[#EDE6E1] flex items-center justify-center shrink-0 overflow-hidden">
                <img
                    src="{{ asset('asset/logo-coffe-senja.png') }}"
                    alt="Logo Cafe Senja"
                    class="w-full h-full object-cover"
                >
            </div>

            <div class="min-w-0">
                <p class="text-[15px] font-semibold text-[#F2ECE8] leading-tight tracking-wide">
                    Coffee Senja
                </p>

                <p class="text-xs text-[#8F827C] mt-1">
                    Manajer
                </p>
            </div>

        </div>

        <div class="mx-5 border-t border-[#29211F] md:mx-4"></div>

        {{-- NAVIGASI --}}
        <nav class="flex-1 px-4 py-4 grid grid-cols-2 gap-1 content-start md:block md:space-y-1 md:overflow-y-auto">

            @php
                $navItems = [
                    [
                        'route' => 'manajer.dashboard',
                        'label' => 'Laporan',
                        'icon' => 'chart'
                    ],
                    [
                        'route' => 'manajer.menu',
                        'label' => 'Menu',
                        'icon' => 'menu'
                    ],
                    [
                        'route' => 'manajer.resep',
                        'label' => 'Resep',
                        'icon' => 'resep'
                    ],
                    [
                        'route' => 'manajer.bahan',
                        'label' => 'Bahan',
                        'icon' => 'bahan'
                    ],
                    [
                        'route' => 'manajer.stok-masuk',
                        'label' => 'Stok masuk',
                        'icon' => 'stok'
                    ],
                ];

                $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
            @endphp

            @foreach($navItems as $item)

                @php
                    $isActive = $currentRoute === $item['route'];
                    $routeExists = \Illuminate\Support\Facades\Route::has($item['route']);
                @endphp

                <a
                    href="{{ $routeExists ? route($item['route']) : '#' }}"

                    @if($isActive)
                        aria-current="page"
                    @endif

                    class="group relative flex items-center gap-3 px-3 h-11 rounded-lg text-[13px] md:text-[14px] font-medium transition-all duration-200
                    {{ $isActive
                        ? 'bg-[#352621] text-[#F2ECE8]'
                        : 'text-[#A99B94] hover:bg-[#211B19] hover:text-[#F2ECE8]'
                    }}"
                >

                    {{-- ACTIVE INDICATOR --}}
                    @if($isActive)
                        <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-r-full bg-[#B43B34]"></span>
                    @endif

                    {{-- ICON --}}
                    <span class="shrink-0 {{ $isActive ? 'text-[#D8756D]' : 'text-[#8F827C] group-hover:text-[#C5A99D]' }}">

                        @switch($item['icon'])

                            @case('chart')
                                <svg class="w-[19px] h-[19px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h4v8H3zm7-5h4v13h-4zm7-5h4v18h-4z"/>
                                </svg>
                                @break

                            @case('menu')
                                <svg class="w-[19px] h-[19px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                @break

                            @case('resep')
                                <svg class="w-[19px] h-[19px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.331 0 4.466.89 6.042 2.355M12 6.042A8.968 8.968 0 0118 3.75c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.331 0-4.466.89-6.042 2.355M12 6.042V20.355"/>
                                </svg>
                                @break

                            @case('bahan')
                                <svg class="w-[19px] h-[19px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                @break

                            @case('stok')
                                <svg class="w-[19px] h-[19px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                                @break

                        @endswitch

                    </span>

                    {{-- LABEL --}}
                    <span class="truncate">
                        {{ $item['label'] }}
                    </span>

                </a>

            @endforeach

        </nav>

        {{-- FOOTER SIDEBAR --}}
        <div class="px-5 py-4 border-t border-[#29211F] md:px-5 md:py-5">

            <div class="flex items-center justify-between gap-3">

                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="flex items-center gap-2 text-[13px] text-[#A99B94] hover:text-[#D8756D] transition-colors cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m-3-3h9m0 0l-3-3m3 3l-3 3"/>
                        </svg>

                        Keluar
                    </button>
                </form>

                <a
                    href="#"
                    class="text-[13px] text-[#8F827C] hover:text-[#EDE6E1] transition-colors"
                >
                    Ganti password
                </a>

            </div>

            <p class="hidden md:block text-[10px] text-[#625751] mt-5">
                © {{ date('Y') }} Coffee Senja
            </p>

        </div>

    </aside>

    {{-- MAIN CONTENT --}}
    <main class="w-full min-w-0 px-4 py-5 sm:px-6 sm:py-6 md:ml-[248px] md:w-[calc(100%-248px)] md:px-7 md:py-7 xl:px-8 xl:py-8">

        <div class="w-full min-w-0 max-w-[1600px] mx-auto">
            @yield('content')
        </div>

    </main>

</div>

</body>
</html>
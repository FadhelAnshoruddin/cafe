<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir') — Coffee Senja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden bg-[#121010] font-[Inter,Arial,sans-serif] text-[#EDE6E1] antialiased">
<div class="min-h-screen md:flex">
    <aside class="flex w-full flex-col border-b border-[#29211F] bg-[#0B0909] md:fixed md:inset-y-0 md:left-0 md:z-40 md:w-[248px] md:border-b-0 md:border-r">
        <div class="flex items-center gap-3 px-5 py-5 md:px-6 md:py-7">
            <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-[#EDE6E1]">
                <img src="{{ asset('asset/logo-coffe-senja.png') }}" alt="Logo Coffee Senja" class="h-full w-full object-cover">
            </div>
            <div>
                <p class="text-[15px] font-semibold tracking-wide text-[#F2ECE8]">Coffee Senja</p>
                <p class="mt-1 text-xs text-[#8F827C]">Kasir</p>
            </div>
        </div>
        <div class="mx-5 border-t border-[#29211F] md:mx-4"></div>
        <nav class="grid flex-1 grid-cols-2 content-start gap-1 px-4 py-4 md:block md:space-y-1">
            <a href="{{ route('kasir.dashboard') }}" @if(request()->routeIs('kasir.dashboard')) aria-current="page" @endif
                class="flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-medium transition-colors {{ request()->routeIs('kasir.dashboard') ? 'bg-[#352621] text-[#F2ECE8]' : 'text-[#A99B94] hover:bg-[#211B19] hover:text-[#F2ECE8]' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan masuk
            </a>
            <a href="{{ route('kasir.orders.create') }}" @if(request()->routeIs('kasir.orders.create')) aria-current="page" @endif
                class="flex h-11 items-center gap-3 rounded-lg px-3 text-sm font-medium transition-colors {{ request()->routeIs('kasir.orders.create') ? 'bg-[#352621] text-[#F2ECE8]' : 'text-[#A99B94] hover:bg-[#211B19] hover:text-[#F2ECE8]' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Buat pesanan
            </a>
        </nav>
        <div class="border-t border-[#29211F] px-5 py-4 md:px-5 md:py-5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-[13px] text-[#A99B94] transition-colors hover:text-[#D8756D]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m-3-3h9m0 0l-3-3m3 3l-3 3"/>
                    </svg>
                    Keluar
                </button>
            </form>
            <p class="mt-5 hidden text-[10px] text-[#625751] md:block">© {{ date('Y') }} Coffee Senja</p>
        </div>
    </aside>
    <main class="w-full min-w-0 px-4 py-5 sm:px-6 sm:py-6 md:ml-[248px] md:w-[calc(100%-248px)] md:px-7 md:py-7 xl:px-8 xl:py-8">
        <div class="mx-auto w-full max-w-[1440px]">
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>

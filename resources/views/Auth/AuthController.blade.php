<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Cafe Senja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#121010] text-[#EDE6E1] font-[Inter,Arial,sans-serif] antialiased">

    <div class="flex min-h-screen">

        {{-- ============================================= --}}
        {{-- PANEL KIRI — Dekorasi & Logo --}}
        {{-- ============================================= --}}
        <div class="hidden lg:flex lg:w-[44%] relative bg-[#0B0909] flex-col items-center justify-center overflow-hidden">

            {{-- Biji kopi dekoratif tersebar --}}
            <img src="{{ asset('asset/aset-bean.svg') }}" alt=""
                 class="absolute top-[6%] left-[6%] w-10 opacity-50 rotate-[20deg] pointer-events-none select-none" />
            <img src="{{ asset('asset/aset-bean.svg') }}" alt=""
                 class="absolute top-[10%] right-[15%] w-8 opacity-45 -rotate-[35deg] pointer-events-none select-none" />
            <img src="{{ asset('asset/aset-bean.svg') }}" alt=""
                 class="absolute bottom-[10%] left-[12%] w-9 opacity-50 rotate-[60deg] pointer-events-none select-none" />
            <img src="{{ asset('asset/aset-bean.svg') }}" alt=""
                 class="absolute bottom-[6%] right-[14%] w-10 opacity-45 rotate-[10deg] pointer-events-none select-none" />
            <img src="{{ asset('asset/aset-bean.svg') }}" alt=""
                 class="absolute top-[4%] left-[46%] w-6 opacity-35 rotate-[40deg] pointer-events-none select-none" />
            <img src="{{ asset('asset/aset-bean.svg') }}" alt=""
                 class="absolute bottom-[4%] left-[46%] w-7 opacity-35 -rotate-[20deg] pointer-events-none select-none" />

            {{-- Logo Cafe Senja --}}
            <div class="relative z-10 flex flex-col items-center px-8">
                <img
                    src="{{ asset('asset/logo-coffe-senja-transparan.png') }}"
                    alt="Cafe Senja"
                    class="w-72 h-auto mb-8 drop-shadow-2xl"
                />
            </div>

            {{-- Ilustrasi kopi bawah --}}
            <div class="absolute bottom-12 left-0 right-0 flex items-end justify-center gap-6 px-12">
                <img src="{{ asset('asset/aset-croissant.svg') }}" alt=""
                     class="w-20 opacity-95 pointer-events-none select-none" />
                <img src="{{ asset('asset/aset-cup.svg') }}" alt=""
                     class="w-24 opacity-100 pointer-events-none select-none" />
                <img src="{{ asset('asset/aset-cookie.svg') }}" alt=""
                     class="w-18 opacity-95 pointer-events-none select-none" />
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- PANEL KANAN — Form Login --}}
        {{-- ============================================= --}}
        <div class="flex-1 flex items-center justify-center px-6 py-12">

            <div class="w-full max-w-md space-y-8">

                {{-- Logo untuk mobile --}}
                <div class="lg:hidden flex justify-center mb-6">
                    <img
                        src="{{ asset('asset/logo-coffe-senja-transparan.png') }}"
                        alt="Cafe Senja"
                        class="w-40 h-auto"
                    />
                </div>

                {{-- Heading --}}
                <div class="text-center lg:text-left">
                    <h1 class="text-[26px] font-semibold tracking-tight text-[#EDE6E1]">
                        Masuk sebagai staf
                    </h1>
                </div>

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-400">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Form Login --}}
                <form method="POST" action="{{ route('login.process') }}" class="space-y-6">
                    @csrf

                    {{-- Username --}}
                    <div class="space-y-2">
                        <label for="username" class="block text-[13px] font-medium text-[#CFC4BE]">
                            Username
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            autofocus
                            placeholder="kasir"
                            class="login-input"
                        />
                    </div>

                    {{-- Password --}}
                    <div class="space-y-2">
                        <label for="password" class="block text-[13px] font-medium text-[#CFC4BE]">
                            Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="login-input"
                        />
                    </div>

                    {{-- Tombol Masuk --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="login-button"
                        >
                            Masuk
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</body>
</html>

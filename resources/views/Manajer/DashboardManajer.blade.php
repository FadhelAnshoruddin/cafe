@extends('Manajer.layouts.app')

@section('title', 'Dashboard Manajer')

@section('content')

<div class="mb-7">

    <p class="text-xs font-medium text-[#A77B70] uppercase tracking-wider mb-1">
        Coffee Senja
    </p>

    <h1 class="text-2xl font-bold text-[#F2ECE8]">
        Dashboard Manajer
    </h1>

    <p class="text-sm text-[#8F827C] mt-1">
        Selamat datang di halaman manajemen Cafe Senja.
    </p>

</div>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">
        <p class="text-sm text-[#8F827C]">
            Total Menu
        </p>

        <h3 class="text-2xl font-bold text-[#F2ECE8] mt-3">
            5
        </h3>

        <p class="text-xs text-[#8F827C] mt-1">
            Produk terdaftar
        </p>
    </div>

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">
        <p class="text-sm text-[#8F827C]">
            Total Resep
        </p>

        <h3 class="text-2xl font-bold text-[#F2ECE8] mt-3">
            4
        </h3>

        <p class="text-xs text-[#8F827C] mt-1">
            Resep tersedia
        </p>
    </div>

    <div class="rounded-xl border border-[#49302B] bg-[#211918] p-5">
        <p class="text-sm text-[#C58A7F]">
            Stok Menipis
        </p>

        <h3 class="text-2xl font-bold text-[#D8756D] mt-3">
            3
        </h3>

        <p class="text-xs text-[#A77B73] mt-1">
            Perlu diperiksa
        </p>
    </div>

    <div class="rounded-xl border border-[#304034] bg-[#1B201C] p-5">
        <p class="text-sm text-[#8DB394]">
            Total Bahan
        </p>

        <h3 class="text-2xl font-bold text-[#8DB394] mt-3">
            9
        </h3>

        <p class="text-xs text-[#78927D] mt-1">
            Jenis bahan
        </p>
    </div>

</div>

{{-- NAVIGATION CARDS --}}
<div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">

    <h2 class="text-base font-semibold text-[#F2ECE8] mb-4">
        Menu Manajemen
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        <a href="{{ route('manajer.menu') }}"
           class="rounded-lg border border-[#342B28] bg-[#211C1A] p-4 hover:border-[#A8322D] transition-colors">

            <h3 class="text-sm font-semibold text-[#EDE6E1]">
                Manajemen Menu
            </h3>

            <p class="text-xs text-[#8F827C] mt-2">
                Kelola produk dan harga jual.
            </p>

        </a>

        <a href="{{ route('manajer.resep') }}"
           class="rounded-lg border border-[#342B28] bg-[#211C1A] p-4 hover:border-[#A8322D] transition-colors">

            <h3 class="text-sm font-semibold text-[#EDE6E1]">
                Manajemen Resep
            </h3>

            <p class="text-xs text-[#8F827C] mt-2">
                Kelola komposisi bahan.
            </p>

        </a>

        <a href="{{ route('manajer.bahan') }}"
           class="rounded-lg border border-[#342B28] bg-[#211C1A] p-4 hover:border-[#A8322D] transition-colors">

            <h3 class="text-sm font-semibold text-[#EDE6E1]">
                Manajemen Bahan
            </h3>

            <p class="text-xs text-[#8F827C] mt-2">
                Pantau persediaan bahan.
            </p>

        </a>

        <a href="{{ route('manajer.stok-masuk') }}"
           class="rounded-lg border border-[#342B28] bg-[#211C1A] p-4 hover:border-[#A8322D] transition-colors">

            <h3 class="text-sm font-semibold text-[#EDE6E1]">
                Stok Masuk
            </h3>

            <p class="text-xs text-[#8F827C] mt-2">
                Lihat riwayat pembelian bahan.
            </p>

        </a>

    </div>

</div>

@endsection
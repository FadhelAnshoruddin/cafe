@extends('Manajer.layouts.app')

@section('title', 'Menu')

@section('content')

@php
    $menus = [
        ['nama' => 'Latte', 'kategori' => 'Minuman', 'harga' => 28000, 'hpp' => 11400],
        ['nama' => 'Cappuccino', 'kategori' => 'Minuman', 'harga' => 28000, 'hpp' => 10900],
        ['nama' => 'Es kopi senja', 'kategori' => 'Minuman', 'harga' => 24000, 'hpp' => 9800],
        ['nama' => 'Roti bakar', 'kategori' => 'Makanan', 'harga' => 18000, 'hpp' => 9100],
        ['nama' => 'Teh tarik', 'kategori' => 'Minuman', 'harga' => 15000, 'hpp' => 4200],
    ];

    $totalMenu = count($menus);

    $rataHarga = collect($menus)->avg('harga');
@endphp

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">

    <div>

        <p class="text-xs font-medium text-[#A77B70] uppercase tracking-wider mb-1">
            Manajemen Produk
        </p>

        <h1 class="text-2xl font-bold text-[#F2ECE8]">
            Menu
        </h1>

        <p class="text-sm text-[#8F827C] mt-1">
            Kelola produk, harga jual, dan biaya produksi cafe.
        </p>

    </div>

    <button type="button"
        class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-lg bg-[#A8322D] text-sm font-semibold text-white hover:bg-[#BD3C35] transition-colors cursor-pointer">

        <span class="text-lg leading-none">+</span>
        Tambah menu

    </button>

</div>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">

        <p class="text-sm text-[#8F827C]">
            Total menu
        </p>

        <h3 class="text-2xl font-bold text-[#F2ECE8] mt-3">
            {{ $totalMenu }}
        </h3>

        <p class="text-xs text-[#8F827C] mt-1">
            Produk terdaftar
        </p>

    </div>

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">

        <p class="text-sm text-[#8F827C]">
            Rata-rata harga jual
        </p>

        <h3 class="text-2xl font-bold text-[#D6CCC5] mt-3">
            Rp{{ number_format($rataHarga, 0, ',', '.') }}
        </h3>

        <p class="text-xs text-[#8F827C] mt-1">
            Seluruh produk
        </p>

    </div>

</div>

{{-- TABLE CONTAINER --}}
<div class="rounded-xl border border-[#342B28] bg-[#1C1918] overflow-hidden">

    {{-- TABLE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-5 border-b border-[#342B28]">

        <div>

            <h2 class="text-base font-semibold text-[#F2ECE8]">
                Daftar menu
            </h2>

            <p class="text-xs text-[#8F827C] mt-1">
                Informasi harga dan biaya produksi setiap menu.
            </p>

        </div>

        <input
            type="text"
            id="searchMenu"
            placeholder="Cari menu..."
            class="w-full sm:w-52 h-9 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] placeholder:text-[#756A65] focus:outline-none focus:border-[#A8322D] transition-colors"
        >

    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[850px] text-left">

            <thead>
                <tr class="bg-[#211C1A]">

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">
                        Nama menu
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">
                        Kategori
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Harga jual
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        HPP
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Margin
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-center">
                        Aksi
                    </th>

                </tr>
            </thead>

            <tbody id="tabelMenu" class="divide-y divide-[#342B28]/70">

                @foreach($menus as $menu)

                    @php
                        $margin = $menu['harga'] - $menu['hpp'];

                        $persenMargin = $menu['harga'] > 0
                            ? ($margin / $menu['harga']) * 100
                            : 0;
                    @endphp

                    <tr class="hover:bg-[#29211F]/60 transition-colors">

                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-[#EDE6E1]">
                                {{ $menu['nama'] }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-md bg-[#352824] text-[#C5A99D] text-xs">
                                {{ $menu['kategori'] }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-right text-sm font-semibold text-[#EDE6E1]">
                            Rp{{ number_format($menu['harga'], 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-right text-sm text-[#A99B94]">
                            Rp{{ number_format($menu['hpp'], 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-right">

                            <div class="text-sm font-medium text-[#86B394]">
                                Rp{{ number_format($margin, 0, ',', '.') }}
                            </div>

                            <div class="text-[11px] text-[#8F827C] mt-0.5">
                                {{ number_format($persenMargin, 0) }}%
                            </div>

                        </td>

                        <td class="px-5 py-4">

                            <div class="flex items-center justify-center gap-3">

                                <button type="button"
                                    class="text-xs font-medium text-[#B5A9A3] hover:text-white transition-colors cursor-pointer">
                                    Edit
                                </button>

                                <button type="button"
                                    class="text-xs font-medium text-[#D8756D] hover:text-red-300 transition-colors cursor-pointer">
                                    Hapus
                                </button>

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    {{-- FOOTER --}}
    <div class="px-5 py-3 border-t border-[#342B28]">

        <p class="text-xs text-[#81746E]">
            Menampilkan {{ $totalMenu }} produk.
        </p>

    </div>

</div>

{{-- SEARCH SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('searchMenu');
    const rows = document.querySelectorAll('#tabelMenu tr');

    if (!search) return;

    search.addEventListener('input', function () {

        const keyword = this.value.toLowerCase();

        rows.forEach(function (row) {

            row.style.display = row.textContent.toLowerCase().includes(keyword)
                ? ''
                : 'none';

        });

    });

});
</script>

@endsection
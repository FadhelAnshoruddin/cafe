@extends('Manajer.layouts.app')

@section('title', 'Stok Masuk')

@section('content')

@php
    $stokMasuk = [
        [
            'tgl' => '11 Nov 2026',
            'bahan' => 'Biji kopi',
            'jumlah' => '1.000 g',
            'harga' => 150,
            'total' => 150000,
            'supplier' => 'PT Kopi Nusantara'
        ],
        [
            'tgl' => '11 Nov 2026',
            'bahan' => 'Susu UHT',
            'jumlah' => '6 liter',
            'harga' => 18000,
            'total' => 108000,
            'supplier' => 'UD Segar Jaya'
        ],
        [
            'tgl' => '10 Nov 2026',
            'bahan' => 'Gelas',
            'jumlah' => '200 pcs',
            'harga' => 800,
            'total' => 160000,
            'supplier' => 'Toko Plastik Maju'
        ],
        [
            'tgl' => '09 Nov 2026',
            'bahan' => 'Gula aren',
            'jumlah' => '2.000 ml',
            'harga' => 60,
            'total' => 120000,
            'supplier' => 'UD Aren Murni'
        ],
        [
            'tgl' => '08 Nov 2026',
            'bahan' => 'Roti tawar',
            'jumlah' => '40 lembar',
            'harga' => 1500,
            'total' => 60000,
            'supplier' => 'Bakery Harmoni'
        ],
        [
            'tgl' => '07 Nov 2026',
            'bahan' => 'Mentega',
            'jumlah' => '500 g',
            'harga' => 80,
            'total' => 40000,
            'supplier' => 'UD Segar Jaya'
        ],
    ];

    $totalTransaksi = count($stokMasuk);

    $totalPengeluaran = collect($stokMasuk)
        ->sum('total');
@endphp

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">

    <div>

        <p class="text-xs font-medium text-[#A77B70] uppercase tracking-wider mb-1">
            Manajemen Persediaan
        </p>

        <h1 class="text-2xl font-bold text-[#F2ECE8]">
            Stok Masuk
        </h1>

        <p class="text-sm text-[#8F827C] mt-1">
            Catatan pembelian dan penambahan persediaan bahan.
        </p>

    </div>

    <button type="button"
        class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-lg bg-[#A8322D] text-sm font-semibold text-white hover:bg-[#BD3C35] transition-colors cursor-pointer">

        <span class="text-lg leading-none">+</span>
        Catat stok masuk

    </button>

</div>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">

        <p class="text-sm text-[#8F827C]">
            Total transaksi
        </p>

        <h3 class="text-2xl font-bold text-[#F2ECE8] mt-3">
            {{ $totalTransaksi }}
        </h3>

        <p class="text-xs text-[#8F827C] mt-1">
            Riwayat stok masuk
        </p>

    </div>

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">

        <p class="text-sm text-[#8F827C]">
            Total pembelian
        </p>

        <h3 class="text-2xl font-bold text-[#D6CCC5] mt-3">
            Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
        </h3>

        <p class="text-xs text-[#8F827C] mt-1">
            Akumulasi transaksi yang ditampilkan
        </p>

    </div>

</div>

{{-- TABLE CONTAINER --}}
<div class="rounded-xl border border-[#342B28] bg-[#1C1918] overflow-hidden">

    {{-- TABLE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-5 border-b border-[#342B28]">

        <div>

            <h2 class="text-base font-semibold text-[#F2ECE8]">
                Riwayat stok masuk
            </h2>

            <p class="text-xs text-[#8F827C] mt-1">
                Daftar transaksi penambahan persediaan bahan.
            </p>

        </div>

        <input
            type="text"
            id="searchStok"
            placeholder="Cari bahan atau supplier..."
            class="w-full sm:w-60 h-9 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] placeholder:text-[#756A65] focus:outline-none focus:border-[#A8322D] transition-colors"
        >

    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[900px] text-left">

            <thead>

                <tr class="bg-[#211C1A]">

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">
                        Tanggal
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">
                        Bahan
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Jumlah
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Harga / satuan
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Total
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">
                        Supplier
                    </th>

                </tr>

            </thead>

            <tbody id="tabelStok" class="divide-y divide-[#342B28]/70">

                @foreach($stokMasuk as $stok)

                    <tr class="hover:bg-[#29211F]/60 transition-colors">

                        <td class="px-5 py-4 text-sm text-[#A99B94] whitespace-nowrap">
                            {{ $stok['tgl'] }}
                        </td>

                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-[#EDE6E1]">
                                {{ $stok['bahan'] }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-right text-sm text-[#C5B9B2]">
                            {{ $stok['jumlah'] }}
                        </td>

                        <td class="px-5 py-4 text-right text-sm text-[#A99B94]">
                            Rp{{ number_format($stok['harga'], 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-semibold text-[#EDE6E1]">
                                Rp{{ number_format($stok['total'], 0, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-sm text-[#A99B94]">
                            {{ $stok['supplier'] }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    {{-- FOOTER --}}
    <div class="px-5 py-3 border-t border-[#342B28]">

        <p class="text-xs text-[#81746E]">
            Menampilkan {{ $totalTransaksi }} transaksi.
        </p>

    </div>

</div>

{{-- SEARCH SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('searchStok');
    const rows = document.querySelectorAll('#tabelStok tr');

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
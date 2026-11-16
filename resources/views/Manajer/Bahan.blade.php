@extends('Manajer.layouts.app')

@section('title', 'Bahan')

@section('content')

@php
    $bahans = [
        ['nama' => 'Biji kopi', 'satuan' => 'gram', 'stok' => 450, 'min' => 500, 'harga' => 150],
        ['nama' => 'Susu UHT', 'satuan' => 'liter', 'stok' => 0.3, 'min' => 2, 'harga' => 18000],
        ['nama' => 'Gula', 'satuan' => 'gram', 'stok' => 2000, 'min' => 500, 'harga' => 15],
        ['nama' => 'Gula aren', 'satuan' => 'ml', 'stok' => 800, 'min' => 300, 'harga' => 60],
        ['nama' => 'Roti tawar', 'satuan' => 'lembar', 'stok' => 24, 'min' => 10, 'harga' => 1500],
        ['nama' => 'Mentega', 'satuan' => 'gram', 'stok' => 400, 'min' => 200, 'harga' => 80],
        ['nama' => 'Selai cokelat', 'satuan' => 'gram', 'stok' => 350, 'min' => 200, 'harga' => 120],
        ['nama' => 'Gelas', 'satuan' => 'pcs', 'stok' => 40, 'min' => 40, 'harga' => 800],
        ['nama' => 'Es batu', 'satuan' => 'gram', 'stok' => 5000, 'min' => 1000, 'harga' => 5],
    ];

    $totalBahan = count($bahans);

    $bahanMenipis = collect($bahans)
        ->filter(fn($b) => $b['stok'] <= $b['min'])
        ->count();

    $bahanAman = $totalBahan - $bahanMenipis;
@endphp

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">

    <div>
        <p class="text-xs font-medium text-[#A77B70] uppercase tracking-wider mb-1">
            Manajemen Persediaan
        </p>

        <h1 class="text-2xl font-bold text-[#F2ECE8]">
            Bahan
        </h1>

        <p class="text-sm text-[#8F827C] mt-1">
            Pantau jumlah persediaan dan kebutuhan bahan cafe.
        </p>
    </div>

    <button type="button"
        class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-lg bg-[#A8322D] text-sm font-semibold text-white hover:bg-[#BD3C35] transition-colors cursor-pointer">

        <span class="text-lg leading-none">+</span>
        Tambah bahan

    </button>

</div>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">

        <p class="text-sm text-[#8F827C]">
            Total jenis bahan
        </p>

        <div class="flex items-end justify-between mt-3">

            <h3 class="text-2xl font-bold text-[#F2ECE8]">
                {{ $totalBahan }}
            </h3>

            <span class="text-xs text-[#A99A93]">
                Jenis bahan
            </span>

        </div>

    </div>

    <div class="rounded-xl border border-[#49302B] bg-[#211918] p-5">

        <p class="text-sm text-[#C58A7F]">
            Stok menipis
        </p>

        <div class="flex items-end justify-between mt-3">

            <h3 class="text-2xl font-bold text-[#D8756D]">
                {{ $bahanMenipis }}
            </h3>

            <span class="text-xs text-[#A77B73]">
                Perlu diperiksa
            </span>

        </div>

    </div>

    <div class="rounded-xl border border-[#304034] bg-[#1B201C] p-5">

        <p class="text-sm text-[#8DB394]">
            Stok aman
        </p>

        <div class="flex items-end justify-between mt-3">

            <h3 class="text-2xl font-bold text-[#8DB394]">
                {{ $bahanAman }}
            </h3>

            <span class="text-xs text-[#78927D]">
                Persediaan normal
            </span>

        </div>

    </div>

</div>

{{-- TABLE CONTAINER --}}
<div class="rounded-xl border border-[#342B28] bg-[#1C1918] overflow-hidden">

    {{-- TABLE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-5 border-b border-[#342B28]">

        <div>
            <h2 class="text-base font-semibold text-[#F2ECE8]">
                Daftar bahan
            </h2>

            <p class="text-xs text-[#8F827C] mt-1">
                Informasi stok dan harga setiap bahan.
            </p>
        </div>

        <input
            type="text"
            id="searchBahan"
            placeholder="Cari bahan..."
            class="w-full sm:w-52 h-9 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] placeholder:text-[#756A65] focus:outline-none focus:border-[#A8322D] transition-colors"
        >

    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[850px] text-left">

            <thead>
                <tr class="bg-[#211C1A]">

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">
                        Nama bahan
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">
                        Satuan
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Stok saat ini
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Stok minimum
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Harga / satuan
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-center">
                        Status
                    </th>

                </tr>
            </thead>

            <tbody id="tabelBahan" class="divide-y divide-[#342B28]/70">

                @foreach($bahans as $bahan)

                    @php
                        $isLow = $bahan['stok'] <= $bahan['min'];
                    @endphp

                    <tr class="hover:bg-[#29211F]/60 transition-colors">

                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-[#EDE6E1]">
                                {{ $bahan['nama'] }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-sm text-[#A99B94]">
                            {{ $bahan['satuan'] }}
                        </td>

                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-semibold {{ $isLow ? 'text-[#D8756D]' : 'text-[#D6CCC5]' }}">
                                {{ number_format($bahan['stok'], $bahan['stok'] == intval($bahan['stok']) ? 0 : 1, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-right text-sm text-[#8F827C]">
                            {{ number_format($bahan['min'], 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-right text-sm text-[#C5B9B2]">
                            Rp{{ number_format($bahan['harga'], 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-center">

                            @if($isLow)

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#D8756D]/10 border border-[#D8756D]/20 px-2.5 py-1 text-[11px] font-medium text-[#D8756D]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#D8756D]"></span>
                                    Menipis
                                </span>

                            @else

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#86B394]/10 border border-[#86B394]/20 px-2.5 py-1 text-[11px] font-medium text-[#86B394]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#86B394]"></span>
                                    Aman
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    {{-- FOOTER --}}
    <div class="px-5 py-3 border-t border-[#342B28]">

        <p class="text-xs text-[#81746E]">
            Menampilkan {{ $totalBahan }} jenis bahan.
        </p>

    </div>

</div>

{{-- SEARCH SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('searchBahan');
    const rows = document.querySelectorAll('#tabelBahan tr');

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
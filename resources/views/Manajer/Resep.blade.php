@extends('Manajer.layouts.app')

@section('title', 'Resep')

@section('content')

@php
    $reseps = [
        [
            'menu' => 'Latte',
            'bahan' => [
                ['nama' => 'Biji kopi', 'jumlah' => '18 g'],
                ['nama' => 'Susu UHT', 'jumlah' => '200 ml'],
                ['nama' => 'Gula', 'jumlah' => '10 g'],
                ['nama' => 'Gelas', 'jumlah' => '1 pcs'],
            ],
        ],

        [
            'menu' => 'Cappuccino',
            'bahan' => [
                ['nama' => 'Biji kopi', 'jumlah' => '18 g'],
                ['nama' => 'Susu UHT', 'jumlah' => '150 ml'],
                ['nama' => 'Gula', 'jumlah' => '8 g'],
                ['nama' => 'Gelas', 'jumlah' => '1 pcs'],
            ],
        ],

        [
            'menu' => 'Es kopi senja',
            'bahan' => [
                ['nama' => 'Biji kopi', 'jumlah' => '20 g'],
                ['nama' => 'Susu UHT', 'jumlah' => '100 ml'],
                ['nama' => 'Gula aren', 'jumlah' => '25 ml'],
                ['nama' => 'Es batu', 'jumlah' => '100 g'],
                ['nama' => 'Gelas', 'jumlah' => '1 pcs'],
            ],
        ],

        [
            'menu' => 'Roti bakar',
            'bahan' => [
                ['nama' => 'Roti tawar', 'jumlah' => '2 lembar'],
                ['nama' => 'Mentega', 'jumlah' => '15 g'],
                ['nama' => 'Selai cokelat', 'jumlah' => '20 g'],
            ],
        ],
    ];

    $totalResep = count($reseps);

    $totalKomposisi = collect($reseps)
        ->sum(fn($resep) => count($resep['bahan']));
@endphp

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">

    <div>

        <p class="text-xs font-medium text-[#A77B70] uppercase tracking-wider mb-1">
            Manajemen Produksi
        </p>

        <h1 class="text-2xl font-bold text-[#F2ECE8]">
            Resep
        </h1>

        <p class="text-sm text-[#8F827C] mt-1">
            Atur komposisi bahan untuk setiap produk menu.
        </p>

    </div>

    <button type="button"
        class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-lg bg-[#A8322D] text-sm font-semibold text-white hover:bg-[#BD3C35] transition-colors cursor-pointer">

        <span class="text-lg leading-none">+</span>
        Tambah resep

    </button>

</div>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">

        <p class="text-sm text-[#8F827C]">
            Total resep
        </p>

        <h3 class="text-2xl font-bold text-[#F2ECE8] mt-3">
            {{ $totalResep }}
        </h3>

        <p class="text-xs text-[#8F827C] mt-1">
            Menu dengan komposisi bahan
        </p>

    </div>

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">

        <p class="text-sm text-[#8F827C]">
            Total komposisi
        </p>

        <h3 class="text-2xl font-bold text-[#D6CCC5] mt-3">
            {{ $totalKomposisi }}
        </h3>

        <p class="text-xs text-[#8F827C] mt-1">
            Penggunaan bahan pada seluruh resep
        </p>

    </div>

</div>

{{-- RECIPE CARDS --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

    @foreach($reseps as $resep)

        <div class="rounded-xl border border-[#342B28] bg-[#1C1918] overflow-hidden hover:border-[#51403A] transition-colors">

            {{-- CARD HEADER --}}
            <div class="flex items-center justify-between p-5 border-b border-[#342B28]">

                <div>

                    <h2 class="text-base font-semibold text-[#F2ECE8]">
                        {{ $resep['menu'] }}
                    </h2>

                    <p class="text-xs text-[#8F827C] mt-1">
                        {{ count($resep['bahan']) }} komposisi bahan
                    </p>

                </div>

                {{-- ACTION --}}
                <div class="flex items-center gap-3">

                    <button type="button"
                        class="text-xs font-medium text-[#B5A9A3] hover:text-white transition-colors cursor-pointer">
                        Edit
                    </button>

                    <button type="button"
                        class="text-xs font-medium text-[#D8756D] hover:text-red-300 transition-colors cursor-pointer">
                        Hapus
                    </button>

                </div>

            </div>

            {{-- INGREDIENTS --}}
            <div class="px-5 py-2">

                <div class="flex items-center justify-between py-2">

                    <span class="text-[11px] uppercase tracking-wider font-semibold text-[#81746E]">
                        Bahan
                    </span>

                    <span class="text-[11px] uppercase tracking-wider font-semibold text-[#81746E]">
                        Takaran
                    </span>

                </div>

                <div class="divide-y divide-[#342B28]/60">

                    @foreach($resep['bahan'] as $bahan)

                        <div class="flex items-center justify-between py-3">

                            <div class="flex items-center gap-3">

                                <span class="w-1.5 h-1.5 rounded-full bg-[#A8322D]"></span>

                                <span class="text-sm text-[#C5B9B2]">
                                    {{ $bahan['nama'] }}
                                </span>

                            </div>

                            <span class="text-sm font-medium text-[#EDE6E1]">
                                {{ $bahan['jumlah'] }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    @endforeach

</div>

@endsection
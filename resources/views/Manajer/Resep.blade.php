@extends('Manajer.layouts.app')

@section('title', 'Resep')

@section('content')

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-[26px] font-bold text-[#EDE6E1]">Resep</h1>
        <button class="h-10 px-5 rounded-lg bg-[#9E2B27] text-[14px] font-semibold text-[#EDE6E1] hover:bg-[#b73430] transition-colors cursor-pointer">
            + Tambah resep
        </button>
    </div>

    {{-- Daftar Resep --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
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
        @endphp

        @foreach($reseps as $resep)
            <div class="rounded-lg bg-[#1C1716] border border-[#322927] p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-[15px] font-semibold text-[#EDE6E1]">{{ $resep['menu'] }}</h2>
                    <div class="flex items-center gap-2">
                        <button class="text-[13px] text-[#B5A9A3] hover:text-[#EDE6E1] transition-colors cursor-pointer">Edit</button>
                        <span class="text-[#322927]">·</span>
                        <button class="text-[13px] text-[#D86A63] hover:text-[#EDE6E1] transition-colors cursor-pointer">Hapus</button>
                    </div>
                </div>

                <div class="space-y-2">
                    @foreach($resep['bahan'] as $bahan)
                        <div class="flex items-center justify-between rounded-lg bg-[#2B1A18] px-4 py-2.5">
                            <span class="text-[13px] text-[#B5A9A3]">{{ $bahan['nama'] }}</span>
                            <span class="text-[13px] font-medium text-[#EDE6E1]">{{ $bahan['jumlah'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

@endsection

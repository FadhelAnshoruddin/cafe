@extends('Manajer.layouts.app')

@section('title', 'Bahan')

@section('content')

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-[26px] font-bold text-[#EDE6E1]">Bahan</h1>
        <button class="h-10 px-5 rounded-lg bg-[#9E2B27] text-[14px] font-semibold text-[#EDE6E1] hover:bg-[#b73430] transition-colors cursor-pointer">
            + Tambah bahan
        </button>
    </div>

    {{-- Tabel Bahan --}}
    <div class="rounded-lg bg-[#1C1716] border border-[#322927] p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#322927]">
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480]">Nama bahan</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480]">Satuan</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">Stok saat ini</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">Stok minimum</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">Harga / satuan</th>
                        <th class="py-3 text-[13px] font-semibold text-[#8F8480] text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
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
                    @endphp

                    @foreach($bahans as $bahan)
                        @php
                            $isLow = $bahan['stok'] <= $bahan['min'];
                        @endphp
                        <tr class="border-b border-[#322927]/50 hover:bg-[#2B1A18] transition-colors">
                            <td class="py-3.5 pr-4 text-[14px] font-medium text-[#EDE6E1]">{{ $bahan['nama'] }}</td>
                            <td class="py-3.5 pr-4 text-[14px] text-[#B5A9A3]">{{ $bahan['satuan'] }}</td>
                            <td class="py-3.5 pr-4 text-[14px] text-right {{ $isLow ? 'font-bold text-[#D86A63]' : 'text-[#B5A9A3]' }}">
                                {{ number_format($bahan['stok'], $bahan['stok'] == intval($bahan['stok']) ? 0 : 1, ',', '.') }}
                            </td>
                            <td class="py-3.5 pr-4 text-[14px] text-[#8F8480] text-right">
                                {{ number_format($bahan['min'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 pr-4 text-[14px] text-[#B5A9A3] text-right">
                                Rp{{ number_format($bahan['harga'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[12px] font-bold
                                    {{ $isLow ? 'bg-[#D86A63]/20 text-[#D86A63]' : 'bg-[#8DB394]/20 text-[#8DB394]' }}">
                                    {{ $isLow ? 'Menipis' : 'Aman' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@extends('Manajer.layouts.app')

@section('title', 'Laporan')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-[26px] font-bold text-[#EDE6E1]">Laporan</h1>
        <p class="text-[13px] text-[#8F8480] mt-1">
            Harga bahan diperbarui dari Supplier: {{ now()->format('d M Y H.i') }}
        </p>
    </div>

    {{-- Baris atas: 2 card --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">

        {{-- Card: Penjualan per menu --}}
        <div class="rounded-lg bg-[#1C1716] border border-[#322927] p-6">
            <h2 class="text-[15px] font-semibold text-[#EDE6E1] mb-1">Penjualan per menu · 1–11 Nov</h2>
            <div class="mt-6 space-y-5">
                @php
                    $penjualan = [
                        ['nama' => 'Latte', 'jumlah' => 190, 'persen' => 100],
                        ['nama' => 'Cappuccino', 'jumlah' => 150, 'persen' => 79],
                        ['nama' => 'Es kopi senja', 'jumlah' => 118, 'persen' => 62],
                        ['nama' => 'Roti bakar', 'jumlah' => 76, 'persen' => 40],
                        ['nama' => 'Teh tarik', 'jumlah' => 52, 'persen' => 27],
                    ];
                @endphp

                @foreach($penjualan as $index => $item)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[13px] text-[#B5A9A3]">{{ $item['nama'] }}</span>
                            <span class="text-[13px] font-semibold text-[#EDE6E1]">{{ $item['jumlah'] }}</span>
                        </div>
                        <div class="h-[26px] rounded-md bg-[#4A3F3A] overflow-hidden">
                            <div
                                class="h-full rounded-md {{ $index === 0 ? 'bg-[#9E2B27]' : 'bg-[#4A3F3A]' }}"
                                style="width: {{ $item['persen'] }}%; {{ $index === 0 ? '' : 'background-color: #6b5a52;' }}"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Card: Bahan menipis --}}
        <div class="rounded-lg bg-[#1C1716] border border-[#322927] p-6">
            <h2 class="text-[15px] font-semibold text-[#EDE6E1] mb-4">Bahan menipis</h2>
            <div class="space-y-3">
                @php
                    $bahanMenipis = [
                        ['nama' => 'Susu UHT', 'stok' => '0,3 liter', 'min' => 'min 2 liter'],
                        ['nama' => 'Biji kopi', 'stok' => '450 g', 'min' => 'min 500 g'],
                        ['nama' => 'Gelas', 'stok' => '40 pcs', 'min' => 'min 40 pcs'],
                    ];
                @endphp

                @foreach($bahanMenipis as $bahan)
                    <div class="flex items-center justify-between rounded-lg bg-[#2B1A18] px-5 py-3">
                        <div>
                            <p class="text-[14px] font-medium text-[#EDE6E1]">{{ $bahan['nama'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[14px] font-bold text-[#D86A63]">{{ $bahan['stok'] }}</p>
                            <p class="text-[12px] text-[#8F8480]">{{ $bahan['min'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Card: Margin per menu (tabel) --}}
    <div class="rounded-lg bg-[#1C1716] border border-[#322927] p-6">
        <h2 class="text-[15px] font-semibold text-[#EDE6E1] mb-4">Margin per menu</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#322927]">
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480]">Menu</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">Harga jual</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">HPP</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">Margin</th>
                        <th class="py-3 text-[13px] font-semibold text-[#8F8480] text-right">Margin %</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $margins = [
                            ['menu' => 'Latte', 'harga' => 28000, 'hpp' => 11400, 'margin' => 16600, 'persen' => 59],
                            ['menu' => 'Cappuccino', 'harga' => 28000, 'hpp' => 10900, 'margin' => 17100, 'persen' => 61],
                            ['menu' => 'Es kopi senja', 'harga' => 24000, 'hpp' => 9800, 'margin' => 14200, 'persen' => 59],
                            ['menu' => 'Roti bakar', 'harga' => 18000, 'hpp' => 9100, 'margin' => 8900, 'persen' => 49],
                            ['menu' => 'Teh tarik', 'harga' => 15000, 'hpp' => 4200, 'margin' => 10800, 'persen' => 72],
                        ];
                    @endphp

                    @foreach($margins as $row)
                        <tr class="border-b border-[#322927]/50 hover:bg-[#2B1A18] transition-colors">
                            <td class="py-3.5 pr-4 text-[14px] font-medium text-[#EDE6E1]">{{ $row['menu'] }}</td>
                            <td class="py-3.5 pr-4 text-[14px] text-[#B5A9A3] text-right">Rp{{ number_format($row['harga'], 0, ',', '.') }}</td>
                            <td class="py-3.5 pr-4 text-[14px] text-[#B5A9A3] text-right">Rp{{ number_format($row['hpp'], 0, ',', '.') }}</td>
                            <td class="py-3.5 pr-4 text-[14px] font-medium text-[#8DB394] text-right">Rp{{ number_format($row['margin'], 0, ',', '.') }}</td>
                            <td class="py-3.5 text-[14px] font-bold text-right">
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[12px] font-bold
                                    {{ $row['persen'] >= 60 ? 'bg-[#8DB394]/20 text-[#8DB394]' : ($row['persen'] >= 50 ? 'bg-[#B5A9A3]/20 text-[#B5A9A3]' : 'bg-[#D86A63]/20 text-[#D86A63]') }}">
                                    {{ $row['persen'] }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

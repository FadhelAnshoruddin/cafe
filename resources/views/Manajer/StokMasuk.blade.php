@extends('Manajer.layouts.app')

@section('title', 'Stok Masuk')

@section('content')

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-[26px] font-bold text-[#EDE6E1]">Stok masuk</h1>
        <button class="h-10 px-5 rounded-lg bg-[#9E2B27] text-[14px] font-semibold text-[#EDE6E1] hover:bg-[#b73430] transition-colors cursor-pointer">
            + Catat stok masuk
        </button>
    </div>

    {{-- Tabel Stok Masuk --}}
    <div class="rounded-lg bg-[#1C1716] border border-[#322927] p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#322927]">
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480]">Tanggal</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480]">Bahan</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">Jumlah</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">Harga / satuan</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">Total</th>
                        <th class="py-3 text-[13px] font-semibold text-[#8F8480]">Supplier</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $stokMasuk = [
                            ['tgl' => '11 Nov 2026', 'bahan' => 'Biji kopi', 'jumlah' => '1.000 g', 'harga' => 150, 'total' => 150000, 'supplier' => 'PT Kopi Nusantara'],
                            ['tgl' => '11 Nov 2026', 'bahan' => 'Susu UHT', 'jumlah' => '6 liter', 'harga' => 18000, 'total' => 108000, 'supplier' => 'UD Segar Jaya'],
                            ['tgl' => '10 Nov 2026', 'bahan' => 'Gelas', 'jumlah' => '200 pcs', 'harga' => 800, 'total' => 160000, 'supplier' => 'Toko Plastik Maju'],
                            ['tgl' => '09 Nov 2026', 'bahan' => 'Gula aren', 'jumlah' => '2.000 ml', 'harga' => 60, 'total' => 120000, 'supplier' => 'UD Aren Murni'],
                            ['tgl' => '08 Nov 2026', 'bahan' => 'Roti tawar', 'jumlah' => '40 lembar', 'harga' => 1500, 'total' => 60000, 'supplier' => 'Bakery Harmoni'],
                            ['tgl' => '07 Nov 2026', 'bahan' => 'Mentega', 'jumlah' => '500 g', 'harga' => 80, 'total' => 40000, 'supplier' => 'UD Segar Jaya'],
                        ];
                    @endphp

                    @foreach($stokMasuk as $stok)
                        <tr class="border-b border-[#322927]/50 hover:bg-[#2B1A18] transition-colors">
                            <td class="py-3.5 pr-4 text-[14px] text-[#8F8480]">{{ $stok['tgl'] }}</td>
                            <td class="py-3.5 pr-4 text-[14px] font-medium text-[#EDE6E1]">{{ $stok['bahan'] }}</td>
                            <td class="py-3.5 pr-4 text-[14px] text-[#B5A9A3] text-right">{{ $stok['jumlah'] }}</td>
                            <td class="py-3.5 pr-4 text-[14px] text-[#B5A9A3] text-right">Rp{{ number_format($stok['harga'], 0, ',', '.') }}</td>
                            <td class="py-3.5 pr-4 text-[14px] font-medium text-[#EDE6E1] text-right">Rp{{ number_format($stok['total'], 0, ',', '.') }}</td>
                            <td class="py-3.5 text-[14px] text-[#B5A9A3]">{{ $stok['supplier'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

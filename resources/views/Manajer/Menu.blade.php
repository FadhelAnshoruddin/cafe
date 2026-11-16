@extends('Manajer.layouts.app')

@section('title', 'Menu')

@section('content')

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-[26px] font-bold text-[#EDE6E1]">Menu</h1>
        <button class="h-10 px-5 rounded-lg bg-[#9E2B27] text-[14px] font-semibold text-[#EDE6E1] hover:bg-[#b73430] transition-colors cursor-pointer">
            + Tambah menu
        </button>
    </div>

    {{-- Tabel Menu --}}
    <div class="rounded-lg bg-[#1C1716] border border-[#322927] p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#322927]">
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480]">Nama menu</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480]">Kategori</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">Harga jual</th>
                        <th class="py-3 pr-4 text-[13px] font-semibold text-[#8F8480] text-right">HPP</th>
                        <th class="py-3 text-[13px] font-semibold text-[#8F8480] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $menus = [
                            ['nama' => 'Latte', 'kategori' => 'Minuman', 'harga' => 28000, 'hpp' => 11400],
                            ['nama' => 'Cappuccino', 'kategori' => 'Minuman', 'harga' => 28000, 'hpp' => 10900],
                            ['nama' => 'Es kopi senja', 'kategori' => 'Minuman', 'harga' => 24000, 'hpp' => 9800],
                            ['nama' => 'Roti bakar', 'kategori' => 'Makanan', 'harga' => 18000, 'hpp' => 9100],
                            ['nama' => 'Teh tarik', 'kategori' => 'Minuman', 'harga' => 15000, 'hpp' => 4200],
                        ];
                    @endphp

                    @foreach($menus as $menu)
                        <tr class="border-b border-[#322927]/50 hover:bg-[#2B1A18] transition-colors">
                            <td class="py-3.5 pr-4 text-[14px] font-medium text-[#EDE6E1]">{{ $menu['nama'] }}</td>
                            <td class="py-3.5 pr-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[12px] font-medium bg-[#3A2A23] text-[#B5A9A3]">
                                    {{ $menu['kategori'] }}
                                </span>
                            </td>
                            <td class="py-3.5 pr-4 text-[14px] text-[#B5A9A3] text-right">Rp{{ number_format($menu['harga'], 0, ',', '.') }}</td>
                            <td class="py-3.5 pr-4 text-[14px] text-[#B5A9A3] text-right">Rp{{ number_format($menu['hpp'], 0, ',', '.') }}</td>
                            <td class="py-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="text-[13px] text-[#B5A9A3] hover:text-[#EDE6E1] transition-colors cursor-pointer">Edit</button>
                                    <span class="text-[#322927]">·</span>
                                    <button class="text-[13px] text-[#D86A63] hover:text-[#EDE6E1] transition-colors cursor-pointer">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

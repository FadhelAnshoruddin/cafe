@extends('Kasir.layouts.app')

@section('title', 'Struk Pesanan')

@section('content')
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="mb-1 text-xs font-medium uppercase tracking-wider text-[#A77B70]">Detail transaksi</p>
        <h1 class="text-2xl font-bold text-[#F2ECE8]">Struk Pesanan #{{ $order->id_pesanan }}</h1>
        <p class="mt-1 text-sm text-[#8F827C]">{{ $order->dibuat_pada->format('d M Y, H:i') }} · Meja {{ $order->nomor_meja }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('kasir.dashboard') }}" class="inline-flex h-10 items-center justify-center rounded-lg border border-[#493A35] px-4 text-sm text-[#C5B9B2] transition-colors hover:bg-[#29211F]">Kembali</a>
        <button type="button" onclick="window.print()" class="inline-flex h-10 items-center justify-center rounded-lg bg-[#9E2B27] px-4 text-sm font-semibold text-white transition-colors hover:bg-[#81231F]">Cetak struk</button>
    </div>
</div>

<section class="mx-auto max-w-2xl rounded-xl border border-[#342B28] bg-[#1C1918] p-5 sm:p-8">
    <div class="flex flex-wrap items-start justify-between gap-4 border-b border-[#342B28] pb-5">
        <div>
            <p class="text-sm font-semibold tracking-wide text-[#F2ECE8]">Coffee Senja</p>
            <p class="mt-1 text-xs text-[#8F827C]">Rincian pembayaran</p>
        </div>
        @php
            $statusClass = match ($order->status) {
                'baru' => 'bg-[#352824] text-[#E9BA8A]',
                'diproses' => 'bg-[#382F21] text-[#E6C87E]',
                'selesai' => 'bg-[#203528] text-[#9BC9A7]',
                default => 'bg-[#382321] text-[#E09A92]',
            };
        @endphp
        <span class="inline-flex rounded-full px-2.5 py-1 text-xs {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
    </div>

    <dl class="grid grid-cols-2 gap-x-6 gap-y-3 border-b border-[#342B28] py-5 text-sm sm:grid-cols-4">
        <div><dt class="text-xs text-[#8F827C]">Nomor pesanan</dt><dd class="mt-1 font-medium text-[#EDE6E1]">#{{ $order->id_pesanan }}</dd></div>
        <div><dt class="text-xs text-[#8F827C]">Meja</dt><dd class="mt-1 font-medium text-[#EDE6E1]">{{ $order->nomor_meja }}</dd></div>
        <div><dt class="text-xs text-[#8F827C]">Sumber</dt><dd class="mt-1 font-medium uppercase text-[#EDE6E1]">{{ $order->sumber }}</dd></div>
        <div><dt class="text-xs text-[#8F827C]">Pembayaran</dt><dd class="mt-1 font-medium uppercase text-[#EDE6E1]">{{ $order->metode_bayar }}</dd></div>
    </dl>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[420px] text-left">
            <thead>
                <tr class="border-b border-[#342B28] text-xs text-[#8F827C]">
                    <th class="py-3 pr-3 font-medium">Menu</th>
                    <th class="px-3 py-3 text-right font-medium">Jumlah</th>
                    <th class="px-3 py-3 text-right font-medium">Harga</th>
                    <th class="py-3 pl-3 text-right font-medium">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#342B28]/70">
                @foreach($order->details as $detail)
                    <tr>
                        <td class="py-4 pr-3 text-sm font-medium text-[#EDE6E1]">{{ $detail->nama_menu_snapshot ?: $detail->menu?->nama_menu ?: 'Menu tidak tersedia' }}</td>
                        <td class="px-3 py-4 text-right text-sm text-[#C5B9B2]">{{ $detail->jumlah }}</td>
                        <td class="px-3 py-4 text-right text-sm text-[#A99B94]">Rp{{ number_format((float) $detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="py-4 pl-3 text-right text-sm font-medium text-[#EDE6E1]">Rp{{ number_format((float) $detail->subtotal_item, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <dl class="ml-auto mt-5 max-w-sm space-y-3 border-t border-[#342B28] pt-5 text-sm">
        <div class="flex justify-between gap-4 text-[#A99B94]"><dt>Subtotal</dt><dd>Rp{{ number_format((float) $order->subtotal, 0, ',', '.') }}</dd></div>
        <div class="flex justify-between gap-4 text-[#A99B94]"><dt>Biaya layanan (5%)</dt><dd>Rp{{ number_format((float) $order->biaya_layanan, 0, ',', '.') }}</dd></div>
        <div class="flex justify-between gap-4 text-[#A99B94]"><dt>Pajak (10%)</dt><dd>Rp{{ number_format((float) $order->pajak, 0, ',', '.') }}</dd></div>
        <div class="flex justify-between gap-4 border-t border-[#342B28] pt-3 text-base font-semibold text-[#F2ECE8]"><dt>Total</dt><dd>Rp{{ number_format((float) $order->total, 0, ',', '.') }}</dd></div>
    </dl>
</section>

<style>
@media print {
    body { background: #fff !important; color: #111 !important; }
    aside, main > div > div:first-child > div:last-child { display: none !important; }
    main { margin: 0 !important; width: 100% !important; padding: 0 !important; }
    section { max-width: none !important; border: 0 !important; background: #fff !important; color: #111 !important; }
    section * { color: #111 !important; border-color: #ddd !important; }
}
</style>
@endsection

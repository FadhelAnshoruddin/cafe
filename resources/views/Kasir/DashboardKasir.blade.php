@extends('Kasir.layouts.app')

@section('title', 'Pesanan Masuk')

@section('content')
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="mb-1 text-xs font-medium uppercase tracking-wider text-[#A77B70]">Operasional</p>
        <h1 class="text-2xl font-bold text-[#F2ECE8]">Pesanan Masuk</h1>
        <p class="mt-1 text-sm text-[#8F827C]">Kelola pesanan dan perbarui status sesuai alur.</p>
    </div>
    <a href="{{ route('kasir.orders.create') }}"
        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-[#9E2B27] px-5 text-sm font-semibold text-white transition-colors hover:bg-[#81231F]">
        <span class="text-lg leading-none">+</span> Buat pesanan
    </a>
</div>

@if(session('success'))
    <div role="status" class="mb-4 rounded-lg border border-[#42634B] bg-[#1B2A20] px-4 py-3 text-sm text-[#A9D5B2]">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div role="alert" class="mb-4 rounded-lg border border-[#7A3934] bg-[#2B1B19] px-4 py-3 text-sm text-[#E6A29B]">
        <p class="mb-1 font-semibold">Pesanan belum dapat diproses:</p>
        <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<section class="overflow-hidden rounded-xl border border-[#342B28] bg-[#1C1918]">
    <div class="flex flex-col gap-3 border-b border-[#342B28] p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-base font-semibold text-[#F2ECE8]">Daftar pesanan</h2>
            <p class="mt-1 text-xs text-[#8F827C]">{{ $orders->count() }} pesanan tercatat</p>
        </div>
        <label class="sr-only" for="orderSearch">Cari pesanan</label>
        <input id="orderSearch" type="search" placeholder="Cari meja, nomor, status..."
            class="h-9 w-full rounded-lg border border-[#3A302D] bg-[#141110] px-3 text-sm text-[#EDE6E1] placeholder:text-[#756A65] focus:border-[#9E2B27] focus:outline-none sm:w-64">
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[1050px] text-left">
            <thead>
                <tr class="bg-[#211C1A]">
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Pesanan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Waktu</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Meja</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Sumber</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-[#958780]">Total</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Pembayaran</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-[#958780]">Status</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-[#958780]">Aksi</th>
                </tr>
            </thead>
            <tbody id="orderRows" class="divide-y divide-[#342B28]/70">
                @forelse($orders as $order)
                    @php
                        $statusClass = match ($order->status) {
                            'baru' => 'bg-[#352824] text-[#E9BA8A]',
                            'diproses' => 'bg-[#382F21] text-[#E6C87E]',
                            'selesai' => 'bg-[#203528] text-[#9BC9A7]',
                            default => 'bg-[#382321] text-[#E09A92]',
                        };
                    @endphp
                    <tr class="transition-colors hover:bg-[#29211F]/60">
                        <td class="px-5 py-4 text-sm font-semibold text-[#EDE6E1]">#{{ $order->id_pesanan }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-[#A99B94]">{{ $order->dibuat_pada->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-4 text-sm text-[#C5B9B2]">Meja {{ $order->nomor_meja }}</td>
                        <td class="px-5 py-4 text-sm uppercase text-[#A99B94]">{{ $order->sumber }}</td>
                        <td class="px-5 py-4 text-right text-sm font-semibold text-[#EDE6E1]">Rp{{ number_format((float) $order->total, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-sm uppercase text-[#A99B94]">{{ $order->metode_bayar }}</td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('kasir.orders.show', $order) }}" class="text-xs font-medium text-[#B5A9A3] hover:text-white">Struk</a>
                                @if($order->status === 'baru')
                                    <form method="POST" action="{{ route('kasir.orders.status', $order) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs font-medium text-[#E6C87E] hover:text-amber-200">Proses</button>
                                    </form>
                                    <form method="POST" action="{{ route('kasir.orders.destroy', $order) }}" onsubmit="return confirm('Batalkan pesanan ini dan kembalikan stok?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-[#D8756D] hover:text-red-300">Batalkan</button>
                                    </form>
                                @elseif($order->status === 'diproses')
                                    <form method="POST" action="{{ route('kasir.orders.status', $order) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs font-medium text-[#86B394] hover:text-green-300">Selesaikan</button>
                                    </form>
                                @else
                                    <span class="text-xs text-[#756A65]">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-5 py-12 text-center text-sm text-[#8F827C]">Belum ada pesanan. Buat pesanan untuk memulai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<script>
document.getElementById('orderSearch').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('#orderRows tr').forEach((row) => {
        row.hidden = !row.textContent.toLowerCase().includes(keyword);
    });
});
</script>
@endsection
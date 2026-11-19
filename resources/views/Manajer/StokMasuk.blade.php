@extends('Manajer.layouts.app')

@section('title', 'Stok Masuk')

@section('content')
@php
    $totalPembelian = $stockIns->sum(fn ($stockIn) => (float) $stockIn->jumlah * (float) $stockIn->harga_per_satuan_dasar);
@endphp

<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="mb-1 text-xs font-medium uppercase tracking-wider text-[#A77B70]">Manajemen Persediaan</p>
        <h1 class="text-2xl font-bold text-[#F2ECE8]">Stok Masuk</h1>
        <p class="mt-1 text-sm text-[#8F827C]">Catatan pembelian dan penambahan persediaan bahan.</p>
    </div>
    <button type="button" onclick="openStockInModal('create')" @disabled($ingredients->isEmpty())
        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-[#A8322D] px-5 text-sm font-semibold text-white transition-colors hover:bg-[#BD3C35] disabled:cursor-not-allowed disabled:opacity-50">
        <span class="text-lg leading-none">+</span> Catat stok masuk
    </button>
</div>

@if(session('success'))
    <div role="status" class="mb-4 rounded-lg border border-[#42634B] bg-[#1B2A20] px-4 py-3 text-sm text-[#A9D5B2]">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div role="alert" class="mb-4 rounded-lg border border-[#7A3934] bg-[#2B1B19] px-4 py-3 text-sm text-[#E6A29B]">{{ session('error') }}</div>
@endif
@if($errors->any())
    <div role="alert" class="mb-4 rounded-lg border border-[#7A3934] bg-[#2B1B19] px-4 py-3 text-sm text-[#E6A29B]">
        <p class="mb-1 font-semibold">Data belum dapat disimpan:</p>
        <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">
        <p class="text-sm text-[#8F827C]">Total transaksi</p>
        <h2 class="mt-3 text-2xl font-bold text-[#F2ECE8]">{{ $stockIns->count() }}</h2>
        <p class="mt-1 text-xs text-[#8F827C]">Riwayat stok masuk</p>
    </div>
    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">
        <p class="text-sm text-[#8F827C]">Total pembelian tercatat</p>
        <h2 class="mt-3 text-2xl font-bold text-[#D6CCC5]">Rp{{ number_format($totalPembelian, 0, ',', '.') }}</h2>
        <p class="mt-1 text-xs text-[#8F827C]">Akumulasi jumlah dikali harga per satuan</p>
    </div>
</div>

@if($ingredients->isEmpty())
    <div class="mb-5 rounded-lg border border-[#493A35] bg-[#211C1A] px-4 py-3 text-sm text-[#C5B9B2]">
        Tambahkan data bahan terlebih dahulu sebelum mencatat stok masuk.
    </div>
@endif

<section class="overflow-hidden rounded-xl border border-[#342B28] bg-[#1C1918]">
    <div class="flex flex-col gap-3 border-b border-[#342B28] p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-semibold text-[#F2ECE8]">Riwayat stok masuk</h2>
            <p class="mt-1 text-xs text-[#8F827C]">Stok bahan disesuaikan otomatis saat transaksi dibuat, diubah, atau dihapus.</p>
        </div>
        <input id="searchStockIn" type="search" placeholder="Cari bahan atau supplier..."
            class="h-9 w-full rounded-lg border border-[#3A302D] bg-[#141110] px-3 text-sm text-[#EDE6E1] placeholder:text-[#756A65] focus:border-[#A8322D] focus:outline-none sm:w-60">
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[1050px] text-left">
            <thead>
                <tr class="bg-[#211C1A]">
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Tanggal</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Bahan</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-[#958780]">Jumlah</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-[#958780]">Harga / satuan</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-[#958780]">Total</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Supplier</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-[#958780]">Aksi</th>
                </tr>
            </thead>
            <tbody id="stockInRows" class="divide-y divide-[#342B28]/70">
                @forelse($stockIns as $stockIn)
                    @if($stockIn->ingredient)
                        <tr class="transition-colors hover:bg-[#29211F]/60">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-[#A99B94]">{{ $stockIn->tanggal->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-sm font-medium text-[#EDE6E1]">{{ $stockIn->ingredient->nama_bahan }}</td>
                            <td class="px-5 py-4 text-right text-sm text-[#C5B9B2]">
                                {{ number_format((float) $stockIn->jumlah, 3, ',', '.') }} {{ $stockIn->ingredient->satuan_dasar }}
                            </td>
                            <td class="px-5 py-4 text-right text-sm text-[#A99B94]">Rp{{ number_format((float) $stockIn->harga_per_satuan_dasar, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right text-sm font-semibold text-[#EDE6E1]">
                                Rp{{ number_format((float) $stockIn->jumlah * (float) $stockIn->harga_per_satuan_dasar, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-sm text-[#A99B94]">{{ $stockIn->supplier ?: '—' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button"
                                        onclick="openStockInModal('edit', {{ $stockIn->id_stok_masuk }}, @js($stockIn->id_bahan), @js($stockIn->tanggal->format('Y-m-d')), @js((string) $stockIn->jumlah), @js((string) $stockIn->harga_per_satuan_dasar), @js($stockIn->supplier ?? ''))"
                                        class="text-xs font-medium text-[#B5A9A3] hover:text-white">Edit</button>
                                    <form method="POST" action="{{ route('manajer.stok-masuk.destroy', $stockIn->id_stok_masuk) }}"
                                        onsubmit="return confirm('Hapus catatan stok masuk ini? Jumlah stok akan dikurangi kembali.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-[#D8756D] hover:text-red-300">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-sm text-[#8F827C]">Belum ada transaksi stok masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-[#342B28] px-5 py-3">
        <p id="stockInCount" class="text-xs text-[#81746E]">Menampilkan {{ $stockIns->count() }} transaksi.</p>
    </div>
</section>

<div id="stockInModal" class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/70 px-4 py-6">
    <div class="my-auto w-full max-w-2xl rounded-xl border border-[#493A35] bg-[#1C1918] shadow-2xl">
        <div class="flex items-center justify-between border-b border-[#342B28] px-5 py-4">
            <h2 id="stockInModalTitle" class="text-lg font-semibold text-[#F2ECE8]">Catat stok masuk</h2>
            <button type="button" onclick="closeStockInModal()" aria-label="Tutup" class="text-2xl leading-none text-[#8F827C] hover:text-white">&times;</button>
        </div>
        <form id="stockInForm" method="POST" action="{{ route('manajer.stok-masuk.store') }}" class="p-5">
            @csrf
            <input id="stockInMethod" type="hidden" name="_method" value="PUT" disabled>
            <input id="stockInId" type="hidden" name="stock_in_id">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="stockInIngredient" class="mb-1.5 block text-sm text-[#C5B8B1]">Bahan</label>
                    <select id="stockInIngredient" name="id_bahan" required
                        class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
                        <option value="">Pilih bahan</option>
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id_bahan }}">{{ $ingredient->nama_bahan }} ({{ $ingredient->satuan_dasar }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="stockInDate" class="mb-1.5 block text-sm text-[#C5B8B1]">Tanggal</label>
                    <input id="stockInDate" name="tanggal" type="date" required
                        class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
                </div>
                <div>
                    <label for="stockInQuantity" class="mb-1.5 block text-sm text-[#C5B8B1]">Jumlah masuk</label>
                    <input id="stockInQuantity" name="jumlah" type="number" min="0.001" step="0.001" required
                        class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
                </div>
                <div>
                    <label for="stockInPrice" class="mb-1.5 block text-sm text-[#C5B8B1]">Harga per satuan dasar (Rp)</label>
                    <input id="stockInPrice" name="harga_per_satuan_dasar" type="number" min="0" step="0.0001" required
                        class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
                </div>
                <div class="sm:col-span-2">
                    <label for="stockInSupplier" class="mb-1.5 block text-sm text-[#C5B8B1]">Supplier <span class="text-[#8F827C]">(opsional)</span></label>
                    <input id="stockInSupplier" name="supplier" maxlength="255"
                        class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
                </div>
            </div>
            <div class="mt-5 flex justify-end gap-2 border-t border-[#342B28] pt-4">
                <button type="button" onclick="closeStockInModal()" class="h-10 rounded-lg border border-[#493A35] px-4 text-sm text-[#C5B8B1] hover:bg-[#29211F]">Batal</button>
                <button id="stockInSubmit" type="submit" class="h-10 rounded-lg bg-[#A8322D] px-5 text-sm font-semibold text-white hover:bg-[#BD3C35]">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openStockInModal(mode, id = null, ingredientId = '', date = '', quantity = '', price = '', supplier = '') {
    const modal = document.getElementById('stockInModal');
    const form = document.getElementById('stockInForm');
    const method = document.getElementById('stockInMethod');
    const editing = mode === 'edit';

    document.getElementById('stockInModalTitle').textContent = editing ? 'Edit stok masuk' : 'Catat stok masuk';
    document.getElementById('stockInSubmit').textContent = editing ? 'Simpan perubahan' : 'Simpan';
    form.action = editing ? @json(url('/manajer/stok-masuk')) + '/' + encodeURIComponent(id) : @json(route('manajer.stok-masuk.store'));
    method.disabled = !editing;
    document.getElementById('stockInId').value = editing ? id : '';
    document.getElementById('stockInIngredient').value = ingredientId;
    document.getElementById('stockInDate').value = date;
    document.getElementById('stockInQuantity').value = quantity;
    document.getElementById('stockInPrice').value = price;
    document.getElementById('stockInSupplier').value = supplier;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    document.getElementById('stockInIngredient').focus();
}

function closeStockInModal() {
    const modal = document.getElementById('stockInModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

document.getElementById('stockInModal').addEventListener('click', function (event) {
    if (event.target === this) closeStockInModal();
});
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') closeStockInModal();
});

document.getElementById('searchStockIn').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const rows = [...document.querySelectorAll('#stockInRows tr')];
    let visibleRows = 0;

    rows.forEach((row) => {
        const isVisible = row.textContent.toLowerCase().includes(keyword);
        row.hidden = !isVisible;
        if (isVisible) visibleRows++;
    });

    document.getElementById('stockInCount').textContent = `Menampilkan ${visibleRows} transaksi.`;
});

@if($errors->any())
openStockInModal(
    @js(old('_method') === 'PUT' ? 'edit' : 'create'),
    @js(old('stock_in_id')),
    @js(old('id_bahan', '')),
    @js(old('tanggal', '')),
    @js(old('jumlah', '')),
    @js(old('harga_per_satuan_dasar', '')),
    @js(old('supplier', ''))
);
@endif
</script>
@endsection

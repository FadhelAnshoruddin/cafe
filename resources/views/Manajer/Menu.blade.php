@extends('Manajer.layouts.app')

@section('title', 'Menu')

@section('content')
@php
    $rupiah = fn ($nilai) => 'Rp' . number_format((float) $nilai, 0, ',', '.');
@endphp

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">
    <div>
        <p class="text-xs font-medium text-[#A77B70] uppercase tracking-wider mb-1">Manajemen Produk</p>
        <h1 class="text-2xl font-bold text-[#F2ECE8]">Menu</h1>
        <p class="text-sm text-[#8F827C] mt-1">Kelola produk, harga jual, dan biaya produksi cafe.</p>
    </div>

    <button type="button" onclick="openMenuModal('create')"
        class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-lg bg-[#A8322D] text-sm font-semibold text-white hover:bg-[#BD3C35] transition-colors">
        <span class="text-lg leading-none">+</span> Tambah menu
    </button>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-[#42634B] bg-[#1B2A20] px-4 py-3 text-sm text-[#A9D5B2]">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 rounded-lg border border-[#7A3934] bg-[#2B1B19] px-4 py-3 text-sm text-[#E6A29B]">{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="mb-4 rounded-lg border border-[#7A3934] bg-[#2B1B19] px-4 py-3 text-sm text-[#E6A29B]">
        <p class="font-semibold mb-1">Data belum dapat disimpan:</p>
        <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">
        <p class="text-sm text-[#8F827C]">Total menu</p>
        <h3 class="text-2xl font-bold text-[#F2ECE8] mt-3">{{ $totalMenu }}</h3>
        <p class="text-xs text-[#8F827C] mt-1">Produk terdaftar</p>
    </div>
    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">
        <p class="text-sm text-[#8F827C]">Rata-rata harga jual</p>
        <h3 class="text-2xl font-bold text-[#D6CCC5] mt-3">{{ $rupiah($rataHarga) }}</h3>
        <p class="text-xs text-[#8F827C] mt-1">Seluruh produk</p>
    </div>
</div>

{{-- TABLE --}}
<div class="rounded-xl border border-[#342B28] bg-[#1C1918] overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-5 border-b border-[#342B28]">
        <div>
            <h2 class="text-base font-semibold text-[#F2ECE8]">Daftar menu</h2>
            <p class="text-xs text-[#8F827C] mt-1">Informasi harga, biaya produksi, dan status menu.</p>
        </div>
        <form method="GET" action="{{ route('manajer.menu') }}" class="flex gap-2">
            <input type="text" name="cari" value="{{ $pencarian }}" placeholder="Cari menu..."
                class="w-full sm:w-56 h-9 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] placeholder:text-[#756A65] focus:outline-none focus:border-[#A8322D]">
            <button class="h-9 px-3 rounded-lg border border-[#493A35] text-xs text-[#D6CCC5] hover:bg-[#29211F]">Cari</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[980px] text-left">
            <thead>
                <tr class="bg-[#211C1A]">
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Nama menu</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">Kategori</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">Harga jual</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">HPP</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">Margin</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-center">Status</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#342B28]/70">
                @forelse($menus as $menu)
                    <tr class="hover:bg-[#29211F]/60 transition-colors">
                        <td class="px-5 py-4 text-sm font-medium text-[#EDE6E1]">{{ $menu->nama_menu }}</td>
                        <td class="px-5 py-4"><span class="inline-flex px-2.5 py-1 rounded-md bg-[#352824] text-[#C5A99D] text-xs">{{ $menu->kategori }}</span></td>
                        <td class="px-5 py-4 text-right text-sm font-semibold text-[#EDE6E1]">{{ $rupiah($menu->harga_jual) }}</td>
                        <td class="px-5 py-4 text-right text-sm text-[#A99B94]">{{ $rupiah($menu->hpp_hitung) }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="text-sm font-medium {{ $menu->margin_hitung >= 0 ? 'text-[#86B394]' : 'text-[#D8756D]' }}">{{ $rupiah($menu->margin_hitung) }}</div>
                            <div class="text-[11px] text-[#8F827C] mt-0.5">{{ number_format($menu->persen_margin, 0, ',', '.') }}%</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($menu->aktif)
                                <span class="inline-flex px-2.5 py-1 rounded-full bg-[#203528] text-[#9BC9A7] text-xs">Aktif</span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full bg-[#382321] text-[#E09A92] text-xs">Non aktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-3">
                                <button type="button"
                                    onclick="openMenuModal('edit', {{ $menu->id_menu }}, @js($menu->nama_menu), @js($menu->kategori), @js((string) $menu->harga_jual))"
                                    class="text-xs font-medium text-[#B5A9A3] hover:text-white transition-colors">Edit</button>
                                <form method="POST" action="{{ route('manajer.menu.status', $menu->id_menu) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-xs font-semibold {{ $menu->aktif ? 'text-[#D8756D] hover:text-red-300' : 'text-[#86B394] hover:text-green-300' }}"
                                        onclick="return confirm('Yakin ingin {{ $menu->aktif ? 'menonaktifkan' : 'mengaktifkan' }} menu ini?')">
                                        {{ $menu->aktif ? 'Non aktif' : 'Aktif' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-sm text-[#8F827C]">Belum ada menu yang cocok dengan pencarian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-[#342B28]">
        <p class="text-xs text-[#81746E]">Menampilkan {{ $menus->count() }} dari {{ $totalMenu }} produk.</p>
    </div>
</div>

{{-- MODAL CREATE / UPDATE --}}
<div id="menuModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/70 px-4 py-6">
    <div class="w-full max-w-lg rounded-xl border border-[#493A35] bg-[#1C1918] shadow-2xl">
        <div class="flex items-center justify-between border-b border-[#342B28] px-5 py-4">
            <h2 id="menuModalTitle" class="text-lg font-semibold text-[#F2ECE8]">Tambah menu</h2>
            <button type="button" onclick="closeMenuModal()" class="text-2xl leading-none text-[#8F827C] hover:text-white">&times;</button>
        </div>
        <form id="menuForm" method="POST" action="{{ route('manajer.menu.store') }}" class="p-5 space-y-4">
            @csrf
            <input type="hidden" id="menuMethod" name="_method" value="POST" disabled>
            <div>
                <label for="nama_menu" class="mb-1.5 block text-sm text-[#C5B8B1]">Nama menu</label>
                <input id="nama_menu" name="nama_menu" required maxlength="255"
                    class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
            </div>
            <div>
                <label for="kategori" class="mb-1.5 block text-sm text-[#C5B8B1]">Kategori</label>
                <input id="kategori" name="kategori" required maxlength="100" placeholder="Contoh: Minuman"
                    class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
            </div>
            <div>
                <label for="harga_jual" class="mb-1.5 block text-sm text-[#C5B8B1]">Harga jual (Rp)</label>
                <input id="harga_jual" name="harga_jual" type="number" min="0" step="0.01" required
                    class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
            </div>
            <div class="flex justify-end gap-2 border-t border-[#342B28] pt-4">
                <button type="button" onclick="closeMenuModal()" class="h-10 rounded-lg border border-[#493A35] px-4 text-sm text-[#C5B8B1] hover:bg-[#29211F]">Batal</button>
                <button id="menuSubmit" type="submit" class="h-10 rounded-lg bg-[#A8322D] px-5 text-sm font-semibold text-white hover:bg-[#BD3C35]">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openMenuModal(mode, id = null, nama = '', kategori = '', harga = '') {
    const modal = document.getElementById('menuModal');
    const form = document.getElementById('menuForm');
    const method = document.getElementById('menuMethod');
    const editing = mode === 'edit';

    document.getElementById('menuModalTitle').textContent = editing ? 'Edit menu' : 'Tambah menu';
    document.getElementById('menuSubmit').textContent = editing ? 'Simpan perubahan' : 'Simpan';
    form.action = editing
        ? @json(url('/manajer/menu')) + '/' + encodeURIComponent(id)
        : @json(route('manajer.menu.store'));

    method.disabled = !editing;
    method.value = 'PUT';
    document.getElementById('nama_menu').value = nama;
    document.getElementById('kategori').value = kategori;
    document.getElementById('harga_jual').value = harga;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    document.getElementById('nama_menu').focus();
}

function closeMenuModal() {
    const modal = document.getElementById('menuModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

document.getElementById('menuModal').addEventListener('click', function (event) {
    if (event.target === this) closeMenuModal();
});
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') closeMenuModal();
});
</script>
@endsection

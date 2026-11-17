@extends('Manajer.layouts.app')

@section('title', 'Bahan')

@section('content')

@php
    $totalBahan = $ingredients->count();

    $bahanMenipis = $ingredients->filter(function ($bahan) {
        return $bahan->stok <= $bahan->stok_minimum;
    })->count();

    $bahanAman = $totalBahan - $bahanMenipis;
@endphp

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">

    <div>
        <p class="text-xs font-medium text-[#A77B70] uppercase tracking-wider mb-1">
            Manajemen Persediaan
        </p>

        <h1 class="text-2xl font-bold text-[#F2ECE8]">
            Bahan
        </h1>

        <p class="text-sm text-[#8F827C] mt-1">
            Pantau jumlah persediaan dan kebutuhan bahan cafe.
        </p>
    </div>

    <button type="button"
        onclick="document.getElementById('formTambahBahan').classList.toggle('hidden')"
        class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-lg bg-[#A8322D] text-sm font-semibold text-white hover:bg-[#BD3C35] transition-colors cursor-pointer">

        <span class="text-lg leading-none">+</span>
        Tambah bahan

    </button>

</div>


{{-- PESAN SUKSES DAN ERROR --}}
@if(session('success'))
    <div class="mb-5 p-3 rounded-lg bg-green-900/30 border border-green-700 text-green-300 text-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-5 p-3 rounded-lg bg-red-900/30 border border-red-700 text-red-300 text-sm">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-5 p-3 rounded-lg bg-red-900/30 border border-red-700 text-red-300 text-sm">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- FORM TAMBAH BAHAN --}}
<div id="formTambahBahan"
    class="hidden rounded-xl border border-[#342B28] bg-[#1C1918] p-5 mb-6">

    <h2 class="text-base font-semibold text-[#F2ECE8] mb-4">
        Tambah Bahan Baru
    </h2>

    <form action="{{ route('manajer.bahan.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div>
                <label class="block text-sm text-[#A99B94] mb-2">
                    Nama Bahan
                </label>

                <input type="text"
                    name="nama_bahan"
                    value="{{ old('nama_bahan') }}"
                    required
                    class="w-full h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] focus:outline-none focus:border-[#A8322D]">
            </div>

            <div>
                <label class="block text-sm text-[#A99B94] mb-2">
                    Satuan
                </label>

                <select name="satuan_dasar"
                    required
                    class="w-full h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] focus:outline-none focus:border-[#A8322D]">

                    <option value="">Pilih satuan</option>
                    <option value="g" {{ old('satuan_dasar') == 'g' ? 'selected' : '' }}>g</option>
                    <option value="ml" {{ old('satuan_dasar') == 'ml' ? 'selected' : '' }}>ml</option>
                    <option value="pcs" {{ old('satuan_dasar') == 'pcs' ? 'selected' : '' }}>pcs</option>

                </select>
            </div>

            <div>
                <label class="block text-sm text-[#A99B94] mb-2">
                    Stok Saat Ini
                </label>

                <input type="number"
                    name="stok"
                    value="{{ old('stok') }}"
                    min="0"
                    step="0.001"
                    required
                    class="w-full h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] focus:outline-none focus:border-[#A8322D]">
            </div>

            <div>
                <label class="block text-sm text-[#A99B94] mb-2">
                    Stok Minimum
                </label>

                <input type="number"
                    name="stok_minimum"
                    value="{{ old('stok_minimum') }}"
                    min="0"
                    step="0.001"
                    required
                    class="w-full h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] focus:outline-none focus:border-[#A8322D]">
            </div>

            <div>
                <label class="block text-sm text-[#A99B94] mb-2">
                    Harga per Satuan
                </label>

                <input type="number"
                    name="harga_per_satuan_dasar"
                    value="{{ old('harga_per_satuan_dasar') }}"
                    min="0"
                    step="0.01"
                    required
                    class="w-full h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] focus:outline-none focus:border-[#A8322D]">
            </div>

        </div>

        <div class="flex gap-2 mt-5">

            <button type="submit"
                class="px-5 py-2 rounded-lg bg-[#A8322D] text-sm font-semibold text-white hover:bg-[#BD3C35]">
                Simpan Bahan
            </button>

            <button type="button"
                onclick="document.getElementById('formTambahBahan').classList.add('hidden')"
                class="px-5 py-2 rounded-lg bg-[#342B28] text-sm text-[#EDE6E1]">
                Batal
            </button>

        </div>
    </form>

</div>


{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">
        <p class="text-sm text-[#8F827C]">
            Total jenis bahan
        </p>

        <div class="flex items-end justify-between mt-3">
            <h3 class="text-2xl font-bold text-[#F2ECE8]">
                {{ $totalBahan }}
            </h3>

            <span class="text-xs text-[#A99A93]">
                Jenis bahan
            </span>
        </div>
    </div>


    <div class="rounded-xl border border-[#49302B] bg-[#211918] p-5">
        <p class="text-sm text-[#C58A7F]">
            Stok menipis
        </p>

        <div class="flex items-end justify-between mt-3">
            <h3 class="text-2xl font-bold text-[#D8756D]">
                {{ $bahanMenipis }}
            </h3>

            <span class="text-xs text-[#A77B73]">
                Perlu diperiksa
            </span>
        </div>
    </div>


    <div class="rounded-xl border border-[#304034] bg-[#1B201C] p-5">
        <p class="text-sm text-[#8DB394]">
            Stok aman
        </p>

        <div class="flex items-end justify-between mt-3">
            <h3 class="text-2xl font-bold text-[#8DB394]">
                {{ $bahanAman }}
            </h3>

            <span class="text-xs text-[#78927D]">
                Persediaan normal
            </span>
        </div>
    </div>

</div>


{{-- TABLE CONTAINER --}}
<div class="rounded-xl border border-[#342B28] bg-[#1C1918] overflow-hidden">

    {{-- TABLE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-5 border-b border-[#342B28]">

        <div>
            <h2 class="text-base font-semibold text-[#F2ECE8]">
                Daftar bahan
            </h2>

            <p class="text-xs text-[#8F827C] mt-1">
                Informasi stok dan harga setiap bahan.
            </p>
        </div>

        <input
            type="text"
            id="searchBahan"
            placeholder="Cari bahan..."
            class="w-full sm:w-52 h-9 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1] placeholder:text-[#756A65] focus:outline-none focus:border-[#A8322D] transition-colors">

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[950px] text-left">

            <thead>
                <tr class="bg-[#211C1A]">

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">
                        Nama bahan
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780]">
                        Satuan
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Stok saat ini
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Stok minimum
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-right">
                        Harga / satuan
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-center">
                        Status
                    </th>

                    <th class="px-5 py-3 text-xs font-semibold text-[#958780] text-center">
                        Aksi
                    </th>

                </tr>
            </thead>


            <tbody id="tabelBahan" class="divide-y divide-[#342B28]/70">

                @forelse($ingredients as $bahan)

                    @php
                        $isLow = $bahan->stok <= $bahan->stok_minimum;
                    @endphp

                    <tr class="hover:bg-[#29211F]/60 transition-colors">

                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-[#EDE6E1]">
                                {{ $bahan->nama_bahan }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-sm text-[#A99B94]">
                            {{ $bahan->satuan_dasar }}
                        </td>

                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-semibold {{ $isLow ? 'text-[#D8756D]' : 'text-[#D6CCC5]' }}">
                                {{ number_format($bahan->stok, 3, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-right text-sm text-[#8F827C]">
                            {{ number_format($bahan->stok_minimum, 3, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-right text-sm text-[#C5B9B2]">
                            Rp{{ number_format($bahan->harga_per_satuan_dasar, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-center">

                            @if($isLow)

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#D8756D]/10 border border-[#D8756D]/20 px-2.5 py-1 text-[11px] font-medium text-[#D8756D]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#D8756D]"></span>
                                    Menipis
                                </span>

                            @else

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[#86B394]/10 border border-[#86B394]/20 px-2.5 py-1 text-[11px] font-medium text-[#86B394]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#86B394]"></span>
                                    Aman
                                </span>

                            @endif

                        </td>


                        {{-- AKSI --}}
                        <td class="px-5 py-4 text-center">

                            <button type="button"
                                onclick="document.getElementById('editBahan{{ $bahan->id_bahan }}').classList.toggle('hidden')"
                                class="text-xs text-blue-400 hover:text-blue-300 mr-2">
                                Edit
                            </button>

                            <form action="{{ route('manajer.bahan.destroy', $bahan->id_bahan) }}"
                                method="POST"
                                class="inline"
                                onsubmit="return confirm('Yakin ingin menghapus bahan ini?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-xs text-red-400 hover:text-red-300">
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>


                    {{-- FORM EDIT --}}
                    <tr id="editBahan{{ $bahan->id_bahan }}" class="hidden bg-[#211C1A]">

                        <td colspan="7" class="p-5">

                            <h3 class="text-sm font-semibold text-[#F2ECE8] mb-4">
                                Edit Bahan: {{ $bahan->nama_bahan }}
                            </h3>

                            <form action="{{ route('manajer.bahan.update', $bahan->id_bahan) }}"
                                method="POST">

                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                    <input type="text"
                                        name="nama_bahan"
                                        value="{{ $bahan->nama_bahan }}"
                                        required
                                        class="h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1]">

                                    <select name="satuan_dasar"
                                        required
                                        class="h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1]">

                                        <option value="g" {{ $bahan->satuan_dasar == 'g' ? 'selected' : '' }}>g</option>
                                        <option value="ml" {{ $bahan->satuan_dasar == 'ml' ? 'selected' : '' }}>ml</option>
                                        <option value="pcs" {{ $bahan->satuan_dasar == 'pcs' ? 'selected' : '' }}>pcs</option>

                                    </select>

                                    <input type="number"
                                        name="stok"
                                        value="{{ $bahan->stok }}"
                                        min="0"
                                        step="0.001"
                                        required
                                        class="h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1]">

                                    <input type="number"
                                        name="stok_minimum"
                                        value="{{ $bahan->stok_minimum }}"
                                        min="0"
                                        step="0.001"
                                        required
                                        class="h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1]">

                                    <input type="number"
                                        name="harga_per_satuan_dasar"
                                        value="{{ $bahan->harga_per_satuan_dasar }}"
                                        min="0"
                                        step="0.01"
                                        required
                                        class="h-10 px-3 rounded-lg bg-[#141110] border border-[#3A302D] text-sm text-[#EDE6E1]">

                                </div>

                                <button type="submit"
                                    class="mt-4 px-5 py-2 rounded-lg bg-[#A8322D] text-sm font-semibold text-white hover:bg-[#BD3C35]">
                                    Simpan Perubahan
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-[#8F827C]">
                            Belum ada data bahan. Silakan tambahkan bahan terlebih dahulu.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- FOOTER --}}
    <div class="px-5 py-3 border-t border-[#342B28]">

        <p class="text-xs text-[#81746E]">
            Menampilkan {{ $totalBahan }} jenis bahan.
        </p>

    </div>

</div>


{{-- SEARCH SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('searchBahan');
    const rows = document.querySelectorAll('#tabelBahan tr');

    if (!search) return;

    search.addEventListener('input', function () {

        const keyword = this.value.toLowerCase();

        rows.forEach(function (row) {

            row.style.display = row.textContent.toLowerCase().includes(keyword)
                ? ''
                : 'none';

        });

    });

});
</script>

@endsection
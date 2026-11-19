@extends('Manajer.layouts.app')

@section('title', 'Resep')

@section('content')
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="mb-1 text-xs font-medium uppercase tracking-wider text-[#A77B70]">Manajemen Produksi</p>
        <h1 class="text-2xl font-bold text-[#F2ECE8]">Resep</h1>
        <p class="mt-1 text-sm text-[#8F827C]">Atur komposisi bahan untuk setiap produk menu.</p>
    </div>
    <button type="button" onclick="openRecipeModal('create')"
        @disabled($menus->isEmpty() || $ingredients->isEmpty())
        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-[#A8322D] px-5 text-sm font-semibold text-white transition-colors hover:bg-[#BD3C35] disabled:cursor-not-allowed disabled:opacity-50">
        <span class="text-lg leading-none">+</span> Tambah resep
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
        <p class="text-sm text-[#8F827C]">Menu dengan resep</p>
        <h2 class="mt-3 text-2xl font-bold text-[#F2ECE8]">{{ $recipes->pluck('id_menu')->unique()->count() }}</h2>
    </div>
    <div class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5">
        <p class="text-sm text-[#8F827C]">Total komposisi</p>
        <h2 class="mt-3 text-2xl font-bold text-[#D6CCC5]">{{ $recipes->count() }}</h2>
    </div>
</div>

@if($menus->isEmpty() || $ingredients->isEmpty())
    <div class="mb-5 rounded-lg border border-[#493A35] bg-[#211C1A] px-4 py-3 text-sm text-[#C5B9B2]">
        Tambahkan minimal satu menu dan satu bahan sebelum membuat resep.
    </div>
@endif

<div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
    @forelse($recipes->groupBy('id_menu') as $menuRecipes)
        @php($menu = $menuRecipes->first()->menu)
        @if($menu)
            <section class="overflow-hidden rounded-xl border border-[#342B28] bg-[#1C1918]">
                <header class="flex items-center justify-between border-b border-[#342B28] p-5">
                    <div>
                        <h2 class="font-semibold text-[#F2ECE8]">{{ $menu->nama_menu }}</h2>
                        <p class="mt-1 text-xs text-[#8F827C]">{{ $menuRecipes->count() }} komposisi bahan</p>
                    </div>
                </header>
                <div class="px-5 py-2">
                    <div class="flex justify-between py-2 text-[11px] font-semibold uppercase tracking-wider text-[#81746E]">
                        <span>Bahan</span>
                        <span>Takaran</span>
                    </div>
                    <div class="divide-y divide-[#342B28]/60">
                        @foreach($menuRecipes as $recipe)
                            @if($recipe->ingredient)
                                <div class="flex flex-wrap items-center justify-between gap-3 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#A8322D]"></span>
                                        <span class="text-sm text-[#C5B9B2]">{{ $recipe->ingredient->nama_bahan }}</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm font-medium text-[#EDE6E1]">
                                            {{ number_format((float) $recipe->takaran, 3, ',', '.') }} {{ $recipe->ingredient->satuan_dasar }}
                                        </span>
                                        <button type="button"
                                            onclick="openRecipeModal('edit', {{ $recipe->id_resep }}, @js($recipe->id_menu), @js($recipe->id_bahan), @js((string) $recipe->takaran))"
                                            class="text-xs font-medium text-[#B5A9A3] hover:text-white">Edit</button>
                                        <form method="POST" action="{{ route('manajer.resep.destroy', $recipe->id_resep) }}"
                                            onsubmit="return confirm('Hapus komposisi bahan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-medium text-[#D8756D] hover:text-red-300">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @empty
        <div class="col-span-full rounded-xl border border-[#342B28] bg-[#1C1918] px-5 py-10 text-center text-sm text-[#8F827C]">
            Belum ada komposisi resep. Tambahkan resep untuk mulai menghitung biaya produksi menu.
        </div>
    @endforelse
</div>

<div id="recipeModal" class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto bg-black/70 px-4 py-6">
    <div class="my-auto w-full max-w-lg rounded-xl border border-[#493A35] bg-[#1C1918] shadow-2xl">
        <div class="flex items-center justify-between border-b border-[#342B28] px-5 py-4">
            <h2 id="recipeModalTitle" class="text-lg font-semibold text-[#F2ECE8]">Tambah resep</h2>
            <button type="button" onclick="closeRecipeModal()" aria-label="Tutup" class="text-2xl leading-none text-[#8F827C] hover:text-white">&times;</button>
        </div>
        <form id="recipeForm" method="POST" action="{{ route('manajer.resep.store') }}" class="space-y-4 p-5">
            @csrf
            <input id="recipeMethod" type="hidden" name="_method" value="PUT" disabled>
            <input id="recipeId" type="hidden" name="recipe_id">
            <div>
                <label for="id_menu" class="mb-1.5 block text-sm text-[#C5B8B1]">Menu</label>
                <select id="id_menu" name="id_menu" required
                    class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
                    <option value="">Pilih menu</option>
                    @foreach($menus as $menuOption)
                        <option value="{{ $menuOption->id_menu }}">{{ $menuOption->nama_menu }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="id_bahan" class="mb-1.5 block text-sm text-[#C5B8B1]">Bahan</label>
                <select id="id_bahan" name="id_bahan" required
                    class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
                    <option value="">Pilih bahan</option>
                    @foreach($ingredients as $ingredientOption)
                        <option value="{{ $ingredientOption->id_bahan }}">{{ $ingredientOption->nama_bahan }} ({{ $ingredientOption->satuan_dasar }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="takaran" class="mb-1.5 block text-sm text-[#C5B8B1]">Takaran per menu</label>
                <input id="takaran" name="takaran" type="number" min="0.001" step="0.001" required
                    class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] outline-none focus:border-[#A8322D]">
            </div>
            <div class="flex justify-end gap-2 border-t border-[#342B28] pt-4">
                <button type="button" onclick="closeRecipeModal()" class="h-10 rounded-lg border border-[#493A35] px-4 text-sm text-[#C5B8B1] hover:bg-[#29211F]">Batal</button>
                <button id="recipeSubmit" type="submit" class="h-10 rounded-lg bg-[#A8322D] px-5 text-sm font-semibold text-white hover:bg-[#BD3C35]">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRecipeModal(mode, id = null, menuId = '', ingredientId = '', quantity = '') {
    const modal = document.getElementById('recipeModal');
    const form = document.getElementById('recipeForm');
    const method = document.getElementById('recipeMethod');
    const editing = mode === 'edit';

    document.getElementById('recipeModalTitle').textContent = editing ? 'Edit komposisi resep' : 'Tambah resep';
    document.getElementById('recipeSubmit').textContent = editing ? 'Simpan perubahan' : 'Simpan';
    form.action = editing ? @json(url('/manajer/resep')) + '/' + encodeURIComponent(id) : @json(route('manajer.resep.store'));
    method.disabled = !editing;
    document.getElementById('recipeId').value = editing ? id : '';
    document.getElementById('id_menu').value = menuId;
    document.getElementById('id_bahan').value = ingredientId;
    document.getElementById('takaran').value = quantity;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    document.getElementById('id_menu').focus();
}

function closeRecipeModal() {
    const modal = document.getElementById('recipeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

document.getElementById('recipeModal').addEventListener('click', function (event) {
    if (event.target === this) closeRecipeModal();
});
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') closeRecipeModal();
});
@if($errors->any())
openRecipeModal(
    @js(old('_method') === 'PUT' ? 'edit' : 'create'),
    @js(old('recipe_id')),
    @js(old('id_menu', '')),
    @js(old('id_bahan', '')),
    @js(old('takaran', ''))
);
@endif
</script>
@endsection

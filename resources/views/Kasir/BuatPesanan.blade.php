@extends('Kasir.layouts.app')

@section('title', 'Buat Pesanan')

@section('content')
<div class="mb-7">
    <p class="mb-1 text-xs font-medium uppercase tracking-wider text-[#A77B70]">Operasional</p>
    <h1 class="text-2xl font-bold text-[#F2ECE8]">Buat Pesanan</h1>
    <p class="mt-1 text-sm text-[#8F827C]">Pilih menu tersedia, jumlah, meja, dan metode pembayaran.</p>
</div>

@if($errors->any())
    <div role="alert" class="mb-5 rounded-lg border border-[#7A3934] bg-[#2B1B19] px-4 py-3 text-sm text-[#E6A29B]">
        <p class="mb-1 font-semibold">Pesanan belum dapat diproses:</p>
        <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form id="orderForm" method="POST" action="{{ route('kasir.orders.store') }}" class="grid grid-cols-1 items-start gap-5 xl:grid-cols-[minmax(0,1fr)_360px]">
    @csrf
    <input type="hidden" name="request_key" value="{{ $requestKey }}">

    <section class="overflow-hidden rounded-xl border border-[#342B28] bg-[#1C1918]">
        <div class="flex flex-col gap-3 border-b border-[#342B28] p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-[#F2ECE8]">Daftar menu</h2>
                <p class="mt-1 text-xs text-[#8F827C]">Menu nonaktif atau kehabisan bahan tidak dapat ditambahkan.</p>
            </div>
            <label class="sr-only" for="menuSearch">Cari menu</label>
            <input id="menuSearch" type="search" placeholder="Cari nama atau kategori..."
                class="h-9 w-full rounded-lg border border-[#3A302D] bg-[#141110] px-3 text-sm text-[#EDE6E1] placeholder:text-[#756A65] focus:border-[#9E2B27] focus:outline-none sm:w-64">
        </div>
        <div id="menuGrid" class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 2xl:grid-cols-3">
            @forelse($menus as $menu)
                @php
                    $menuQuantity = collect(old('items', []))->firstWhere('id_menu', $menu->id_menu)['jumlah'] ?? 0;
                @endphp
                <article data-menu-card data-search="{{ strtolower($menu->nama_menu.' '.$menu->kategori) }}"
                    class="flex min-h-[150px] flex-col justify-between rounded-lg border border-[#342B28] bg-[#211C1A] p-4 transition-colors hover:border-[#51403A]">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="inline-flex rounded-md bg-[#352824] px-2 py-1 text-[11px] text-[#C5A99D]">{{ $menu->kategori }}</span>
                            <h3 class="mt-3 font-semibold text-[#EDE6E1]">{{ $menu->nama_menu }}</h3>
                        </div>
                        @if($menu->status_ketersediaan === 'Tersedia')
                            <span class="shrink-0 rounded-full bg-[#203528] px-2 py-1 text-[11px] text-[#9BC9A7]">Tersedia</span>
                        @else
                            <span class="shrink-0 rounded-full bg-[#382321] px-2 py-1 text-[11px] text-[#E09A92]">{{ $menu->status_ketersediaan }}</span>
                        @endif
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-3">
                        <span class="text-sm font-semibold text-[#EDE6E1]">Rp{{ number_format((float) $menu->harga_jual, 0, ',', '.') }}</span>
                        <div class="flex items-center gap-2">
                            <button type="button" data-quantity-down="{{ $menu->id_menu }}" aria-label="Kurangi {{ $menu->nama_menu }}"
                                class="grid h-8 w-8 place-items-center rounded-md border border-[#493A35] text-[#C5B9B2] hover:bg-[#29211F] disabled:opacity-40" @disabled(! $menu->tersedia || ! $menuQuantity)>−</button>
                            <output data-quantity-output="{{ $menu->id_menu }}" class="min-w-5 text-center text-sm text-[#EDE6E1]">{{ $menuQuantity }}</output>
                            <button type="button" data-quantity-up="{{ $menu->id_menu }}" aria-label="Tambah {{ $menu->nama_menu }}"
                                class="grid h-8 w-8 place-items-center rounded-md bg-[#9E2B27] text-white hover:bg-[#81231F] disabled:cursor-not-allowed disabled:opacity-40" @disabled(! $menu->tersedia)>+</button>
                        </div>
                        <input type="hidden" name="items[{{ $menu->id_menu }}][id_menu]" value="{{ $menu->id_menu }}" disabled>
                        <input type="hidden" name="items[{{ $menu->id_menu }}][jumlah]" value="{{ $menuQuantity }}" disabled>
                    </div>
                </article>
            @empty
                <p class="col-span-full rounded-lg border border-dashed border-[#493A35] px-4 py-10 text-center text-sm text-[#8F827C]">Belum ada menu yang dapat ditampilkan.</p>
            @endforelse
        </div>
        <p id="menuSearchEmpty" class="hidden px-5 pb-5 text-center text-sm text-[#8F827C]">Menu tidak ditemukan.</p>
    </section>

    <aside class="rounded-xl border border-[#342B28] bg-[#1C1918] p-5 xl:sticky xl:top-6">
        <h2 class="text-base font-semibold text-[#F2ECE8]">Ringkasan pesanan</h2>
        <div id="cartItems" class="mt-4 space-y-3">
            <p id="cartEmpty" class="rounded-lg border border-dashed border-[#493A35] px-3 py-5 text-center text-sm text-[#8F827C]">Belum ada menu dipilih.</p>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3">
            <div>
                <label for="nomor_meja" class="mb-1.5 block text-sm text-[#C5B8B1]">Nomor meja</label>
                <select id="nomor_meja" name="nomor_meja" required
                    class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] focus:border-[#9E2B27] focus:outline-none">
                    <option value="">Pilih meja</option>
                    @for($tableNumber = 1; $tableNumber <= 10; $tableNumber++)
                        <option value="{{ $tableNumber }}" @selected(old('nomor_meja') == $tableNumber)>Meja {{ $tableNumber }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="metode_bayar" class="mb-1.5 block text-sm text-[#C5B8B1]">Pembayaran</label>
                <select id="metode_bayar" name="metode_bayar" required
                    class="h-10 w-full rounded-lg border border-[#493A35] bg-[#141110] px-3 text-sm text-[#EDE6E1] focus:border-[#9E2B27] focus:outline-none">
                    <option value="">Pilih metode</option>
                    <option value="tunai" @selected(old('metode_bayar') === 'tunai')>Tunai</option>
                    <option value="qris" @selected(old('metode_bayar') === 'qris')>QRIS</option>
                </select>
            </div>
        </div>

        <dl class="mt-5 space-y-3 border-t border-[#342B28] pt-4 text-sm">
            <div class="flex justify-between gap-3 text-[#A99B94]"><dt>Subtotal</dt><dd id="subtotalValue">Rp0</dd></div>
            <div class="flex justify-between gap-3 text-[#A99B94]"><dt>Biaya layanan (5%)</dt><dd id="serviceValue">Rp0</dd></div>
            <div class="flex justify-between gap-3 text-[#A99B94]"><dt>Pajak (10%)</dt><dd id="taxValue">Rp0</dd></div>
            <div class="flex justify-between gap-3 border-t border-[#342B28] pt-3 text-base font-semibold text-[#EDE6E1]"><dt>Total</dt><dd id="totalValue">Rp0</dd></div>
        </dl>
        <p class="mt-3 text-xs leading-relaxed text-[#81746E]">Tunai dan QRIS hanya dicatat sebagai metode pembayaran. Pembayaran diselesaikan di luar sistem.</p>
        <button id="submitOrder" type="submit" disabled
            class="mt-5 h-11 w-full rounded-lg bg-[#9E2B27] px-5 text-sm font-semibold text-white transition-colors hover:bg-[#81231F] disabled:cursor-not-allowed disabled:opacity-40">
            Buat pesanan
        </button>
    </aside>
</form>

<script>
const menuData = @json($menus->map(fn ($menu) => [
    'id' => $menu->id_menu,
    'name' => $menu->nama_menu,
    'price' => (int) round((float) $menu->harga_jual * 100),
]));
const selectedQuantities = new Map(
    [...document.querySelectorAll('[data-quantity-output]')]
        .map((output) => [Number(output.dataset.quantityOutput), Number(output.textContent)])
        .filter(([, quantity]) => quantity > 0)
);
const rupiah = (cents) => new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
}).format(Math.round(cents / 100));

function renderCart() {
    const cart = document.getElementById('cartItems');
    const empty = document.getElementById('cartEmpty');
    cart.querySelectorAll('[data-cart-line]').forEach((line) => line.remove());
    let subtotal = 0;

    selectedQuantities.forEach((quantity, menuId) => {
        const menu = menuData.find((item) => item.id === menuId);
        if (!menu || quantity < 1) return;
        const lineTotal = menu.price * quantity;
        subtotal += lineTotal;
        const line = document.createElement('div');
        line.dataset.cartLine = '';
        line.className = 'flex items-start justify-between gap-3 text-sm';
        const name = document.createElement('span');
        name.className = 'min-w-0 text-[#C5B9B2]';
        name.textContent = `${menu.name} × ${quantity}`;
        const price = document.createElement('span');
        price.className = 'shrink-0 font-medium text-[#EDE6E1]';
        price.textContent = rupiah(lineTotal);
        line.append(name, price);
        cart.append(line);
    });

    empty.classList.toggle('hidden', selectedQuantities.size > 0);
    const service = Math.round(subtotal * 0.05);
    const tax = Math.round((subtotal + service) * 0.10);
    document.getElementById('subtotalValue').textContent = rupiah(subtotal);
    document.getElementById('serviceValue').textContent = rupiah(service);
    document.getElementById('taxValue').textContent = rupiah(tax);
    document.getElementById('totalValue').textContent = rupiah(subtotal + service + tax);
    document.getElementById('submitOrder').disabled = selectedQuantities.size === 0;
}

function updateQuantity(menuId, delta) {
    const currentQuantity = selectedQuantities.get(menuId) ?? 0;
    const nextQuantity = Math.max(0, currentQuantity + delta);
    const article = document.querySelector(`[data-quantity-output="${menuId}"]`).closest('article');
    const output = article.querySelector(`[data-quantity-output="${menuId}"]`);
    const decrement = article.querySelector(`[data-quantity-down="${menuId}"]`);
    const idInput = article.querySelector(`input[name="items[${menuId}][id_menu]"]`);
    const quantityInput = article.querySelector(`input[name="items[${menuId}][jumlah]"]`);

    output.textContent = nextQuantity;
    decrement.disabled = nextQuantity === 0;
    idInput.disabled = nextQuantity === 0;
    quantityInput.disabled = nextQuantity === 0;
    quantityInput.value = nextQuantity;

    if (nextQuantity === 0) selectedQuantities.delete(menuId);
    else selectedQuantities.set(menuId, nextQuantity);

    renderCart();
}

document.querySelectorAll('[data-quantity-up]').forEach((button) => {
    button.addEventListener('click', () => updateQuantity(Number(button.dataset.quantityUp), 1));
});
document.querySelectorAll('[data-quantity-down]').forEach((button) => {
    button.addEventListener('click', () => updateQuantity(Number(button.dataset.quantityDown), -1));
});
document.getElementById('menuSearch').addEventListener('input', function () {
    const keyword = this.value.trim().toLowerCase();
    let visibleCount = 0;
    document.querySelectorAll('[data-menu-card]').forEach((card) => {
        const visible = card.dataset.search.includes(keyword);
        card.hidden = !visible;
        if (visible) visibleCount++;
    });
    document.getElementById('menuSearchEmpty').classList.toggle('hidden', visibleCount > 0);
});
document.getElementById('orderForm').addEventListener('submit', function () {
    document.getElementById('submitOrder').disabled = true;
    document.getElementById('submitOrder').textContent = 'Memproses pesanan...';
});
renderCart();
</script>
@endsection

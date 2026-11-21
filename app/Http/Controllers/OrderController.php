<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('Kasir.DashboardKasir', [
            'orders' => Order::query()
                ->with('details')
                ->orderByDesc('dibuat_pada')
                ->orderByDesc('id_pesanan')
                ->get(),
        ]);
    }

    public function create(OrderService $orderService): View
    {
        return view('Kasir.BuatPesanan', [
            'menus' => $orderService->menuListing(),
            'requestKey' => (string) Str::uuid(),
        ]);
    }

    public function store(Request $request, OrderService $orderService): RedirectResponse
    {
        $data = $request->validate([
            'request_key' => ['required', 'uuid'],
            'nomor_meja' => ['required', 'integer', 'between:1,10'],
            'metode_bayar' => ['required', 'in:tunai,qris'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_menu' => ['required', 'integer', 'distinct', 'exists:menus,id_menu'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $order = $orderService->create($data, $user);

        return redirect()
            ->route('kasir.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show(Order $order): View
    {
        return view('Kasir.StrukPesanan', [
            'order' => $order->load('details.menu'),
        ]);
    }

    public function receipt(Order $order): StreamedResponse
    {
        $order->load('details.menu');

        return response()->streamDownload(function () use ($order): void {
            $receipt = fopen('php://output', 'w');

            if ($receipt === false) {
                throw new \RuntimeException('Struk tidak dapat dibuat.');
            }

            fputcsv($receipt, ['Coffee Senja']);
            fputcsv($receipt, ['Nomor pesanan', $order->id_pesanan]);
            fputcsv($receipt, ['Waktu', $order->dibuat_pada->format('d-m-Y H:i')]);
            fputcsv($receipt, ['Meja', $order->nomor_meja]);
            fputcsv($receipt, ['Sumber', strtoupper($order->sumber)]);
            fputcsv($receipt, ['Metode bayar', strtoupper($order->metode_bayar)]);
            fputcsv($receipt, ['Status', ucfirst($order->status)]);
            fputcsv($receipt, []);
            fputcsv($receipt, ['Menu', 'Jumlah', 'Harga satuan', 'Subtotal']);

            foreach ($order->details as $detail) {
                fputcsv($receipt, [
                    $detail->nama_menu_snapshot ?: $detail->menu?->nama_menu ?: 'Menu tidak tersedia',
                    $detail->jumlah,
                    $detail->harga_satuan,
                    $detail->subtotal_item,
                ]);
            }

            fputcsv($receipt, []);
            fputcsv($receipt, ['Subtotal', $order->subtotal]);
            fputcsv($receipt, ['Biaya layanan', $order->biaya_layanan]);
            fputcsv($receipt, ['Pajak', $order->pajak]);
            fputcsv($receipt, ['Total', $order->total]);
            fclose($receipt);
        }, "struk-pesanan-{$order->id_pesanan}.csv", [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function updateStatus(Order $order, OrderService $orderService): RedirectResponse
    {
        $nextStatus = $order->status === 'baru' ? 'diproses' : 'selesai';
        $orderService->updateStatus($order, $nextStatus);

        return redirect()->route('kasir.dashboard')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order, OrderService $orderService): RedirectResponse
    {
        $orderService->cancel($order);

        return redirect()->route('kasir.dashboard')->with('success', 'Pesanan dibatalkan dan stok dikembalikan.');
    }
}

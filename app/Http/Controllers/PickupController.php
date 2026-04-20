<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;
use App\Models\TransLaundryPickup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PickupController extends Controller
{
    /**
     * Display list of orders ready for pickup (status = 2 / Selesai).
     */
    public function index()
    {
        $pickups = TransLaundryPickup::with(['order', 'customer'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Orders yang belum di-pickup (status 0)
        $ordersReady = TransOrder::with('customer')
            ->where('order_status', 0) // Baru
            ->whereDoesntHave('pickup')
            ->get();

        return view('pickup.index', compact('pickups', 'ordersReady'));
    }

    /**
     * Show form to create pickup for a specific order.
     */
    public function create(Request $request)
    {
        $order = TransOrder::with(['customer', 'details.service'])
            ->where('order_status', 0)
            ->findOrFail($request->order_id);

        return view('pickup.create', compact('order'));
    }

    /**
     * Store pickup record and update order status.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_order'    => 'required|exists:trans_order,id',
            'pickup_date' => 'required|date',
            'notes'       => 'nullable|string|max:500',
        ], [
            'id_order.required'    => 'Order harus dipilih.',
            'pickup_date.required' => 'Tanggal pickup harus diisi.',
        ]);

        DB::beginTransaction();

        try {
            $order = TransOrder::findOrFail($request->id_order);

            // Handle Pelunasan
            $bayarTambahan = $request->input('bayar_pelunasan', 0);
            $totalBayarBaru = $order->order_pay + $bayarTambahan;
            
            // Validasi di server (Double Check)
            if ($totalBayarBaru < $order->total) {
                throw new \Exception('Sisa tagihan belum dilunasi.');
            }

            // Update Transaksi ke Lunas
            $order->update([
                'order_pay' => $totalBayarBaru,
                'order_change' => $totalBayarBaru - $order->total,
                'order_status' => 1 // Sudah Diambil
            ]);

            // Create pickup record
            TransLaundryPickup::create([
                'id_order'    => $order->id,
                'id_customer' => $order->id_customer,
                'pickup_date' => $request->pickup_date,
                'notes'       => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('pickup.index')
                ->with('success', 'Pickup berhasil! Pembayaran dilunasi dan Order ' . $order->order_code . ' telah diambil.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses pickup: ' . $e->getMessage());
        }
    }

    /**
     * Show pickup detail.
     */
    public function show($id)
    {
        $pickup = TransLaundryPickup::with(['order.details.service', 'customer'])->findOrFail($id);
        return view('pickup.show', compact('pickup'));
    }
}

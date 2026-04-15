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

        // Orders yang sudah selesai tapi belum di-pickup
        $ordersReady = TransOrder::with('customer')
            ->where('order_status', 2) // Selesai
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
            ->where('order_status', 2)
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

            // Create pickup record
            TransLaundryPickup::create([
                'id_order'    => $order->id,
                'id_customer' => $order->id_customer,
                'pickup_date' => $request->pickup_date,
                'notes'       => $request->notes,
            ]);

            // Update order status to "Diambil" (3)
            $order->update(['order_status' => 3]);

            DB::commit();

            return redirect()->route('pickup.index')
                ->with('success', 'Pickup berhasil dicatat! Order ' . $order->order_code . ' sudah diambil.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pickup: ' . $e->getMessage());
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

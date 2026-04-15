<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\TypeOfService;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Display a listing of all orders.
     */
    public function index()
    {
        $orders = TransOrder::with(['customer', 'details.service'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transaksi.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $customers = Customer::all();
        $services = TypeOfService::all();

        return view('transaksi.create', compact('customers', 'services'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_customer'          => 'required|exists:customer,id',
            'order_end_date'       => 'required|date',
            'service_id'           => 'required|array|min:1',
            'service_id.*'         => 'required|exists:type_of_service,id',
            'qty'                  => 'required|array|min:1',
            'qty.*'                => 'required|integer|min:1',
            'notes'                => 'nullable|array',
            'notes.*'              => 'nullable|string|max:255',
        ], [
            'id_customer.required' => 'Pilih customer terlebih dahulu.',
            'order_end_date.required' => 'Estimasi Selesai harus diisi.',
            'service_id.required'  => 'Tambahkan minimal 1 layanan.',
            'service_id.min'       => 'Tambahkan minimal 1 layanan.',
            'qty.*.min'            => 'Qty minimal 1.',
        ]);

        DB::beginTransaction();

        try {
            // Generate order code: ORD-YYYYMMDD-XXXX
            $today = Carbon::now();
            $prefix = 'ORD-' . $today->format('Ymd') . '-';
            $lastOrder = TransOrder::where('order_code', 'like', $prefix . '%')
                ->orderBy('order_code', 'desc')
                ->first();

            if ($lastOrder) {
                $lastNumber = (int) substr($lastOrder->order_code, -4);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            $orderCode = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Calculate total from all detail rows
            $grandTotal = 0;
            $detailRows = [];

            foreach ($request->service_id as $i => $serviceId) {
                $service = TypeOfService::findOrFail($serviceId);
                $qty = $request->qty[$i];
                $subtotal = $service->price * $qty;
                $grandTotal += $subtotal;

                $detailRows[] = [
                    'id_service' => $service->id,
                    'qty'        => $qty,
                    'subtotal'   => $subtotal,
                    'notes'      => $request->notes[$i] ?? null,
                ];
            }

            // Create order header
            $order = TransOrder::create([
                'id_customer'    => $request->id_customer,
                'order_code'     => $orderCode,
                'order_date'     => $today->toDateString(),
                'order_end_date' => $request->order_end_date,
                'order_status'   => 0,
                'order_pay'      => 0,
                'order_change'   => 0,
                'total'          => $grandTotal,
            ]);

            // Create order details
            foreach ($detailRows as $row) {
                $order->details()->create($row);
            }

            DB::commit();

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil disimpan! Kode: ' . $orderCode);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = TransOrder::with(['customer', 'details.service'])->findOrFail($id);
        return view('transaksi.show', compact('order'));
    }
    /**
     * Update order status to move forward.
     */
    public function updateStatus($id)
    {
        $order = TransOrder::findOrFail($id);

        if ($order->order_status < 2) {
            $order->update(['order_status' => $order->order_status + 1]);
            return redirect()->route('transaksi.index')->with('success', 'Status transaksi berhasil diupdate!');
        }

        return redirect()->route('transaksi.index')->with('error', 'Status transaksi tidak dapat diupdate lagi.');
    }
}

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
    public function index(Request $request)
    {
        $search = $request->input('search');

        $ordersQuery = TransOrder::with(['customer', 'details.service']);

        if ($search) {
            $ordersQuery->where(function ($q) use ($search) {
                $q->where('order_code', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function ($q2) use ($search) {
                      $q2->where('customer_name', 'like', '%' . $search . '%');
                  });
            });
        }

        $orders = $ordersQuery->orderBy('created_at', 'desc')->get();

        return view('transaksi.index', compact('orders', 'search'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $customers = Customer::withCount('orders')->get();
        $services = TypeOfService::all();

        return view('transaksi.create', compact('customers', 'services'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_mode'        => 'required|in:existing,new',
            'id_customer'          => 'required_if:customer_mode,existing|exclude_if:customer_mode,new|exists:customer,id',
            'new_customer_name'    => 'required_if:customer_mode,new|nullable|string|max:255',
            'new_phone'            => 'required_if:customer_mode,new|nullable|string|max:20|unique:customer,phone',
            'new_address'          => 'required_if:customer_mode,new|nullable|string',
            'order_date'           => 'required|date',
            'order_end_date'       => 'required|date|after_or_equal:order_date',
            'service_id'           => 'required|array|min:1',
            'service_id.*'         => 'required|exists:type_of_service,id',
            'qty'                  => 'required|array|min:1',
            'qty.*'                => 'required|numeric|min:0.1',
            'notes'                => 'nullable|array',
            'notes.*'              => 'nullable|string|max:255',
            'order_pay'            => 'nullable|numeric',
        ], [
            'id_customer.required_if' => 'Pilih customer terlebih dahulu.',
            'new_customer_name.required_if' => 'Nama pelanggan baru harus diisi.',
            'new_phone.required_if' => 'No. Telepon baru harus diisi.',
            'new_phone.unique' => 'Nomor telepon ini sudah terdaftar. Silakan gunakan mode "Member Terdaftar".',
            'new_address.required_if' => 'Alamat baru harus diisi.',
            'order_end_date.required' => 'Estimasi Selesai harus diisi.',
            'service_id.required'  => 'Tambahkan minimal 1 layanan.',
            'qty.*.min'            => 'Qty minimal 0.1.',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total from all detail rows
            $subtotalOrder = 0;
            $detailRows = [];

            foreach ($request->service_id as $i => $serviceId) {
                $service = TypeOfService::findOrFail($serviceId);
                $qty = $request->qty[$i];
                $subtotal = $service->price * $qty;
                $subtotalOrder += $subtotal;

                $detailRows[] = [
                    'id_service' => $service->id,
                    'qty'        => $qty,
                    'subtotal'   => $subtotal,
                    'notes'      => $request->notes[$i] ?? null,
                ];
            }

            // Logic Discount & Tax (FORCED TO 0 FOR CLEAN VERSION)
            $discountAmount = 0;
            $taxAmount = 0;
            $totalOrder = $subtotalOrder;

            // Handle customer discovery or creation
            $customerId = $request->id_customer;
            if ($request->customer_mode == 'new') {
                $newCustomer = Customer::create([
                    'customer_name' => $request->new_customer_name,
                    'phone'         => $request->new_phone,
                    'address'       => $request->new_address,
                ]);
                $customerId = $newCustomer->id;
            }

            // Create order header
            $order = TransOrder::create([
                'id_customer'    => $customerId,
                'is_member'      => 0, // ALWAYS 0 IN CLEAN VERSION
                'order_code'     => $orderCode,
                'order_date'     => $request->order_date ?? $today->toDateString(),
                'order_end_date' => $request->order_end_date,
                'order_status'   => 0,
                'order_pay'      => $request->order_pay ?? 0,
                'order_change'   => $request->order_change ?? 0,
                'tax'            => 0, // ALWAYS 0
                'discount'       => 0, // ALWAYS 0
                'voucher_code'   => null,
                'total'          => $totalOrder,
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

        if ($order->order_status == 0) {
            $order->update(['order_status' => 1]);
            return redirect()->route('transaksi.index')->with('success', 'Transaksi telah selesai dan diserahkan ke pelanggan!');
        }

        return redirect()->route('transaksi.index')->with('error', 'Status transaksi tidak dapat diupdate lagi.');
    }

    /**
     * Remove the specified order from storage (Soft Delete).
     */
    public function destroy($id)
    {
        $order = TransOrder::findOrFail($id);
        $order->delete(); // This will soft delete

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus (Soft Delete).');
    }

    /**
     * Display printable receipt.
     */
    public function receipt($id)
    {
        $order = TransOrder::with(['customer', 'details.service'])->findOrFail($id);
        return view('transaksi.receipt', compact('order'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Get orders within the date range
        $orders = TransOrder::with(['customer', 'details.service'])
            ->whereBetween('order_date', [$startDate, $endDate])
            ->orderBy('order_date', 'asc')
            ->get();

        // Calculate totals
        $totalPendapatan = $orders->sum('total');
        $totalTransaksi = $orders->count();
        $totalSelesai = $orders->whereIn('order_status', [2, 3])->count();
        
        return view('laporan.index', compact(
            'orders', 'startDate', 'endDate', 
            'totalPendapatan', 'totalTransaksi', 'totalSelesai'
        ));
    }
}

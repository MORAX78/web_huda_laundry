<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Monthly statistics (Default view)
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $ordersThisMonth = TransOrder::whereBetween('order_date', [$startOfMonth, $endOfMonth])->get();

        $totalTransaksi = TransOrder::count(); // Total all time
        $totalPendapatan = TransOrder::sum('total'); // Total revenue from transaction values
        $totalBelumDiambil = TransOrder::where('order_status', 0)->count();
        $totalSudahDiambil = TransOrder::where('order_status', 1)->count();

        // Recent Transactions
        $recentOrders = TransOrder::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalTransaksi', 
            'totalPendapatan', 
            'totalBelumDiambil', 
            'totalSudahDiambil',
            'recentOrders'
        ));
    }
}

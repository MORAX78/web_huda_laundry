<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // Stats for current month
        $ordersThisMonth = TransOrder::whereBetween('order_date', [$startOfMonth, $endOfMonth])->get();

        $totalTransaksi = $ordersThisMonth->count();
        $totalPendapatan = $ordersThisMonth->where('order_status', 1)->sum('total');
        $totalSelesai = $ordersThisMonth->where('order_status', 1)->count();

        return view('dashboard', compact('totalTransaksi', 'totalPendapatan', 'totalSelesai'));
    }
}

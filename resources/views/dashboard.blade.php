@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Home</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">
        {{-- Total Transaksi --}}
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Total Transaksi</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="ps-3">
                            <h6 style="font-size: 24px; font-weight: 700;">{{ $totalTransaksi }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3">
                            <h6 style="font-size: 20px; font-weight: 700;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Belum Diambil --}}
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Belum Diambil</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="ps-3">
                            <h6 style="font-size: 24px; font-weight: 700;">{{ $totalBelumDiambil }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sudah Diambil --}}
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Sudah Diambil</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="ps-3">
                            <h6 style="font-size: 24px; font-weight: 700;">{{ $totalSudahDiambil }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Order Code</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $index => $order)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>{{ $order->customer->customer_name }}</td>
                                <td class="text-primary fw-bold">{{ $order->order_code }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                <td>
                                    @if($order->order_status == 0)
                                        <span class="badge bg-warning text-dark">Belum Diambil</span>
                                    @else
                                        <span class="badge bg-success">Sudah Diambil</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body pt-3">
            <p>Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>! </p>
            <p>Anda login sebagai: <span class="badge bg-primary">{{ auth()->user()->level->level_name }}</span></p>
        </div>
    </div>
</section>
@endsection

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
    @if(auth()->user()->level->level_name == 'Pimpinan')
    <div class="row">
        {{-- Total Transaksi --}}
        <div class="col-xxl-4 col-md-4">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Total Transaksi <span>| Bulan Ini</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 64px; height: 64px; font-size: 24px;">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="ps-3">
                            <h6 style="font-size: 28px; font-weight: 700;">{{ $totalTransaksi }}</h6>
                            <span class="text-muted small pt-2 ps-1">transaksi bulan ini</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="col-xxl-4 col-md-4">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan <span>| Bulan Ini</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 64px; height: 64px; font-size: 24px;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3">
                            <h6 style="font-size: 28px; font-weight: 700;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h6>
                            <span class="text-muted small pt-2 ps-1">pendapatan bulan ini</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Selesai --}}
        <div class="col-xxl-4 col-md-4">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Order Selesai <span>| Bulan Ini</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white" style="width: 64px; height: 64px; font-size: 24px;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="ps-3">
                            <h6 style="font-size: 28px; font-weight: 700;">{{ $totalSelesai }}</h6>
                            <span class="text-muted small pt-2 ps-1">order diambil/selesai</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card mt-2">
        <div class="card-body pt-3">
            <p>Selamat datang kembali, <strong>{{ auth()->user()->user_name }}</strong>! </p>
            <p>Anda login sebagai: <span class="badge bg-primary">{{ auth()->user()->level->level_name }}</span></p>
        </div>
    </div>
</section>
@endsection

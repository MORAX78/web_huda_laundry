@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Laporan Transaksi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Laporan</li>
        </ol>
    </nav>
</div>

<section class="section">
    {{-- Filter Form --}}
    <div class="card mb-4">
        <div class="card-body pt-4">
            <form action="{{ route('laporan.index') }}" method="GET" class="row align-items-center">
                <div class="col-md-4 mb-2">
                    <label for="start_date" class="form-label fw-bold">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4 mb-2">
                    <label for="end_date" class="form-label fw-bold">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end mb-2">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-xxl-4 col-md-4">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Total Transaksi</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $totalTransaksi }}</h6>
                            <span class="text-muted small pt-2 ps-1">transaksi dalam periode</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-md-4">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <div class="d-flex align-items-center">
                        <div class="ps-3">
                            <h6>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-md-4">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Order Selesai/Diambil</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $totalSelesai }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Report --}}
    <div class="card">
        <div class="card-body pt-4">
            <h5 class="card-title mb-3">Detail Transaksi ({{ \Carbon\Carbon::parse($startDate)->format('dM Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('dM Y') }})</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover mt-2">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Kode Order</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th class="text-end">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $i => $order)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                            <td><span class="badge bg-secondary">{{ $order->order_code }}</span></td>
                            <td>{{ $order->customer->customer_name ?? '-' }}</td>
                            <td>
                                @if($order->order_status == 0)
                                    <span class="badge bg-warning text-dark">Baru</span>
                                @elseif($order->order_status == 1)
                                    <span class="badge bg-primary">Proses</span>
                                @elseif($order->order_status == 2)
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($order->order_status == 3)
                                    <span class="badge bg-dark">Diambil</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada data transaksi pada rentang tanggal ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($orders->count() > 0)
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="5" class="text-end fw-bold fs-5">KUMULATIF PENDAPATAN:</td>
                            <td class="text-end fw-bold fs-5 text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                 <button class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>
</section>

<style>
/* Sembunyikan elemen selain format cetak saat diprint */
@media print {
    body * {
        visibility: hidden;
    }
    .pagetitle, .breadcrumb, form, button {
        display: none !important; 
    }
    .section, .section * {
        visibility: visible;
    }
    .section {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endsection

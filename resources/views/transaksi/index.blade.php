@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Data Transaksi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Transaksi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                        <h5 class="card-title mb-0">Daftar Transaksi</h5>
                        <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Transaksi
                        </a>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode Order</th>
                                <th>Customer</th>
                                <th>Tgl Order</th>
                                <th>Tgl Selesai</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $i => $order)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><span class="badge bg-info text-dark">{{ $order->order_code }}</span></td>
                                <td>{{ $order->customer->customer_name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->order_end_date)->format('d/m/Y') }}</td>
                                <td class="fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->order_status == 0)
                                        <span class="badge bg-warning text-dark">Baru</span>
                                    @elseif($order->order_status == 1)
                                        <span class="badge bg-primary">Proses</span>
                                    @elseif($order->order_status == 2)
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($order->order_status == 3)
                                        <span class="badge bg-secondary">Diambil</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('transaksi.show', $order->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($order->order_status < 2)
                                        <form action="{{ route('transaksi.update_status', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Update Status">
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Belum ada data transaksi.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Pickup Laundry</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Pickup</li>
        </ol>
    </nav>
</div>

<section class="section">
    {{-- Orders Ready for Pickup --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-box-seam me-1"></i>Order Siap Diambil
                    </h5>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <table class="table table-bordered table-hover">
                        <thead class="table-success">
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode Order</th>
                                <th>Customer</th>
                                <th>Telepon</th>
                                <th>Total</th>
                                <th>Tgl Order</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ordersReady as $i => $order)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><span class="badge bg-success">{{ $order->order_code }}</span></td>
                                <td>{{ $order->customer->customer_name ?? '-' }}</td>
                                <td>{{ $order->customer->phone ?? '-' }}</td>
                                <td class="fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('pickup.create', ['order_id' => $order->id]) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-bag-check me-1"></i>Pickup
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Tidak ada order yang siap diambil.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Pickup History --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-clock-history me-1"></i>Riwayat Pickup
                    </h5>

                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode Order</th>
                                <th>Customer</th>
                                <th>Tgl Pickup</th>
                                <th>Total</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pickups as $i => $pickup)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><span class="badge bg-secondary">{{ $pickup->order->order_code ?? '-' }}</span></td>
                                <td>{{ $pickup->customer->customer_name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($pickup->pickup_date)->format('d/m/Y') }}</td>
                                <td class="fw-bold">Rp {{ number_format($pickup->order->total ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $pickup->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Belum ada riwayat pickup.</p>
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

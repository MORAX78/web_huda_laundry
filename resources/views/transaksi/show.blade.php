@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Detail Transaksi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-4">

                    {{-- Order Header Info --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Kode Order</th>
                                    <td><span class="badge bg-info text-dark fs-6">{{ $order->order_code }}</span></td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td>{{ $order->customer->customer_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $order->customer->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $order->customer->address ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Tanggal Order</th>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Estimasi Selesai</th>
                                    <td>{{ \Carbon\Carbon::parse($order->order_end_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
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
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td class="fw-bold fs-5 text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    {{-- Detail Items --}}
                    <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-1"></i>Detail Layanan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Layanan</th>
                                    <th>Harga Satuan</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $i => $detail)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $detail->service->service_name ?? '-' }}</td>
                                    <td>Rp {{ number_format($detail->service->price ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail->qty }}</td>
                                    <td class="fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    <td>{{ $detail->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-warning">
                                    <td colspan="4" class="text-end fw-bold fs-5">GRAND TOTAL:</td>
                                    <td colspan="2" class="fw-bold fs-5">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4 mb-3">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

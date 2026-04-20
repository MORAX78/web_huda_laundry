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
                        <div class="d-flex gap-2">
                            <form action="{{ route('transaksi.index') }}" method="GET" class="d-flex w-100">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Kode / Customer..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit" title="Cari">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    @if(request('search'))
                                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-danger" title="Reset Pencarian">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                    @endif
                                </div>
                            </form>
                            <a href="{{ route('transaksi.create') }}" class="btn btn-primary" style="white-space: nowrap;">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Transaksi
                            </a>
                        </div>
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
                                <th>Status Order</th>
                                <th>Status Bayar</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $i => $order)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><span class="badge bg-info text-dark">{{ $order->order_code }}</span></td>
                                <td>
                                    {{ $order->customer->customer_name ?? '-' }}
                                    @if($order->is_member)
                                        <small class="text-success" title="Transaksi Member Baru"><i class="bi bi-star-fill"></i></small>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->order_end_date)->format('d/m/Y') }}</td>
                                <td class="fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->order_status == 0)
                                        <span class="badge bg-warning text-dark">Belum Diambil</span>
                                    @elseif($order->order_status == 1)
                                        <span class="badge bg-success">Sudah Diambil</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status_color }}">
                                        {{ $order->payment_status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('transaksi.show', $order->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('transaksi.receipt', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-dark" title="Cetak Struk">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        @if($order->order_status == 0)
                                        <a href="{{ route('pickup.index') }}" class="btn btn-sm btn-success fw-bold" title="Pergi ke Menu Pickup">Pickup</a>
                                        @endif
                                        <form action="{{ route('transaksi.destroy', $order->id) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.form-delete');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data transaksi ini akan disembunyikan (soft-delete).",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection

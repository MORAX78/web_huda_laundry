@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Proses Pickup</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pickup.index') }}">Pickup</a></li>
            <li class="breadcrumb-item active">Proses</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-4">

                    {{-- Order Info --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Kode Order</th>
                                    <td><span class="badge bg-success fs-6">{{ $order->order_code }}</span></td>
                                </tr>
                                <tr>
                                    <th>Customer</th>
                                    <td>{{ $order->customer->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <td>{{ $order->customer->phone }}</td>
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
                                    <th width="40%">Tgl Order</th>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tgl Selesai</th>
                                    <td>{{ \Carbon\Carbon::parse($order->order_end_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td class="fw-bold fs-5 text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Sudah Dibayar</th>
                                    <td class="text-success fw-bold">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</td>
                                </tr>
                                @php $sisaTagihan = $order->total - $order->order_pay; @endphp
                                @if($sisaTagihan > 0)
                                <tr class="table-danger">
                                    <th>Sisa Tagihan</th>
                                    <td class="text-danger fw-bold fs-5">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    {{-- Detail Items --}}
                    <h6 class="fw-bold mb-2">Detail Layanan:</h6>
                    <table class="table table-bordered table-sm table-striped mb-4">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Layanan</th>
                                <th>Qty (Kg)</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $i => $detail)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $detail->service->service_name ?? '-' }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>

                    {{-- Pickup Form --}}
                    <h5 class="fw-bold mb-3"><i class="bi bi-bag-check me-1"></i>Form Pickup</h5>
                    <form action="{{ route('pickup.store') }}" method="POST" id="formPickup">
                        @csrf
                        <input type="hidden" name="id_order" value="{{ $order->id }}">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="pickup_date" class="form-label fw-bold">Tanggal Pickup <span class="text-danger">*</span></label>
                                <input type="date" name="pickup_date" id="pickup_date" class="form-control"
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="notes" class="form-label fw-bold">Catatan</label>
                                <input type="text" name="notes" id="notes" class="form-control"
                                       placeholder="Catatan pickup (opsional)">
                            </div>
                        </div>

                        @if($sisaTagihan > 0)
                        <div class="card bg-light border-danger mb-3">
                            <div class="card-body pt-3">
                                <h6 class="text-danger fw-bold mb-3"><i class="bi bi-cash-stack me-1"></i>Pelunasan Wajib</h6>
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bayar_pelunasan" class="form-label fw-bold">Uang Bayar Pelunasan (Rp) <span class="text-danger">*</span></label>
                                            <input type="number" name="bayar_pelunasan" id="bayar_pelunasan" 
                                                   class="form-control form-control-lg" 
                                                   value="{{ $sisaTagihan }}" min="{{ $sisaTagihan }}" required>
                                            <small class="text-muted">Masukkan nominal pelunasan minimal Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Kembalian</label>
                                            <h4 id="kembalian_display" class="text-success fw-bold">Rp 0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                            <input type="hidden" name="bayar_pelunasan" value="0">
                        @endif

                        <div class="d-flex justify-content-end gap-2 mt-3 mb-3">
                            <a href="{{ route('pickup.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-bag-check me-1"></i> Konfirmasi Pickup
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
@if($sisaTagihan > 0)
    const sisaTagihan = {{ $sisaTagihan }};
    const inputBayar = document.getElementById('bayar_pelunasan');
    const displayKembalian = document.getElementById('kembalian_display');

    function formatRupiah(number) {
        return "Rp " + new Intl.NumberFormat('id-ID').format(number);
    }

    inputBayar.addEventListener('input', function() {
        const bayar = parseInt(this.value) || 0;
        const kembalian = bayar - sisaTagihan;
        displayKembalian.textContent = formatRupiah(kembalian > 0 ? kembalian : 0);
        
        if (bayar < sisaTagihan) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
@endif

document.getElementById('formPickup').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;

    @if($sisaTagihan > 0)
    const bayar = parseInt(inputBayar.value) || 0;
    if (bayar < sisaTagihan) {
        Swal.fire({
            icon: 'error',
            title: 'Pembayaran Kurang!',
            text: 'Sisa tagihan Rp ' + sisaTagihan.toLocaleString() + ' harus dilunasi sebelum pickup.',
            confirmButtonColor: '#3085d6'
        });
        return;
    }
    @endif

    Swal.fire({
        title: 'Konfirmasi Pickup?',
        text: 'Order {{ $order->order_code }} akan ditandai sebagai sudah diambil dan lunas.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-bag-check me-1"></i> Ya, Pickup!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
@endsection

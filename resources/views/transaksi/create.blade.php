@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Tambah Transaksi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pt-4">

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('transaksi.store') }}" method="POST" id="formTransaksi">
                        @csrf

                        {{-- Customer Selection --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_customer" class="form-label fw-bold">
                                    <i class="bi bi-person me-1"></i>Pilih Customer <span class="text-danger">*</span>
                                </label>
                                <select name="id_customer" id="id_customer" class="form-select form-select-lg" required>
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('id_customer') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-calendar me-1"></i>Tanggal Order
                                </label>
                                <input type="text" class="form-control form-control-lg" value="{{ date('d/m/Y') }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="order_end_date" class="form-label fw-bold">
                                    <i class="bi bi-calendar-check me-1"></i>Estimasi Selesai
                                </label>
                                <input type="date" name="order_end_date" id="order_end_date" class="form-control form-control-lg" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
                            </div>
                        </div>

                        <hr>

                        {{-- Detail Items --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-list-check me-1"></i>Detail Layanan
                            </h5>
                            <button type="button" class="btn btn-success" id="btnTambahBaris">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Layanan
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tableDetail">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Layanan</th>
                                        <th width="15%">Harga</th>
                                        <th width="10%">Qty</th>
                                        <th width="15%">Subtotal</th>
                                        <th width="20%">Catatan</th>
                                        <th width="5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyDetail">
                                    <tr class="row-detail" data-row="0">
                                        <td class="text-center row-number">1</td>
                                        <td>
                                            <select name="service_id[]" class="form-select select-service" required>
                                                <option value="">-- Pilih --</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                        {{ $service->service_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control input-harga" readonly placeholder="Rp 0">
                                        </td>
                                        <td>
                                            <input type="number" name="qty[]" class="form-control input-qty" min="1" value="1" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control input-subtotal fw-bold" readonly placeholder="Rp 0">
                                        </td>
                                        <td>
                                            <input type="text" name="notes[]" class="form-control input-notes" placeholder="Opsional">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm btn-hapus-baris" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="table-warning">
                                        <td colspan="4" class="text-end fw-bold fs-5">GRAND TOTAL:</td>
                                        <td colspan="2" class="fw-bold fs-5" id="grandTotalDisplay">Rp 0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end gap-2 mt-4 mb-3">
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="btnSimpan">
                                <i class="bi bi-save me-1"></i> Simpan Transaksi
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Data services for JS --}}
<script>
    const servicesData = @json($services);

    // Format number to Rupiah
    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    // Recalculate row subtotal
    function hitungSubtotal(row) {
        const selectService = row.querySelector('.select-service');
        const inputQty = row.querySelector('.input-qty');
        const inputHarga = row.querySelector('.input-harga');
        const inputSubtotal = row.querySelector('.input-subtotal');

        const selectedOption = selectService.options[selectService.selectedIndex];
        const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
        const qty = parseInt(inputQty.value) || 0;
        const subtotal = price * qty;

        inputHarga.value = formatRupiah(price);
        inputSubtotal.value = formatRupiah(subtotal);

        hitungGrandTotal();
    }

    // Recalculate grand total
    function hitungGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.row-detail').forEach(function(row) {
            const selectService = row.querySelector('.select-service');
            const inputQty = row.querySelector('.input-qty');
            const selectedOption = selectService.options[selectService.selectedIndex];
            const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
            const qty = parseInt(inputQty.value) || 0;
            grandTotal += price * qty;
        });
        document.getElementById('grandTotalDisplay').textContent = formatRupiah(grandTotal);
    }

    // Update row numbers
    function updateRowNumbers() {
        document.querySelectorAll('.row-detail').forEach(function(row, index) {
            row.querySelector('.row-number').textContent = index + 1;
        });
    }

    // Event delegation for dynamic rows
    document.getElementById('tbodyDetail').addEventListener('change', function(e) {
        if (e.target.classList.contains('select-service') || e.target.classList.contains('input-qty')) {
            hitungSubtotal(e.target.closest('.row-detail'));
        }
    });

    document.getElementById('tbodyDetail').addEventListener('input', function(e) {
        if (e.target.classList.contains('input-qty')) {
            hitungSubtotal(e.target.closest('.row-detail'));
        }
    });

    // Add new row
    document.getElementById('btnTambahBaris').addEventListener('click', function() {
        const tbody = document.getElementById('tbodyDetail');
        const rowCount = tbody.querySelectorAll('.row-detail').length;

        let optionsHtml = '<option value="">-- Pilih --</option>';
        servicesData.forEach(function(service) {
            optionsHtml += '<option value="' + service.id + '" data-price="' + service.price + '">' + service.service_name + '</option>';
        });

        const newRow = document.createElement('tr');
        newRow.classList.add('row-detail');
        newRow.setAttribute('data-row', rowCount);
        newRow.innerHTML = `
            <td class="text-center row-number">${rowCount + 1}</td>
            <td>
                <select name="service_id[]" class="form-select select-service" required>
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <input type="text" class="form-control input-harga" readonly placeholder="Rp 0">
            </td>
            <td>
                <input type="number" name="qty[]" class="form-control input-qty" min="1" value="1" required>
            </td>
            <td>
                <input type="text" class="form-control input-subtotal fw-bold" readonly placeholder="Rp 0">
            </td>
            <td>
                <input type="text" name="notes[]" class="form-control input-notes" placeholder="Opsional">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm btn-hapus-baris" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(newRow);
        updateRowNumbers();
    });

    // Delete row
    document.getElementById('tbodyDetail').addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-hapus-baris');
        if (btn) {
            const tbody = document.getElementById('tbodyDetail');
            if (tbody.querySelectorAll('.row-detail').length > 1) {
                btn.closest('.row-detail').remove();
                updateRowNumbers();
                hitungGrandTotal();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Minimal harus ada 1 layanan.',
                    confirmButtonColor: '#3085d6'
                });
            }
        }
    });

    // Confirm before submit
    document.getElementById('formTransaksi').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;

        // Check if at least one service is selected
        let hasService = false;
        document.querySelectorAll('.select-service').forEach(function(select) {
            if (select.value) hasService = true;
        });

        if (!hasService) {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Pilih minimal 1 layanan.',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        Swal.fire({
            title: 'Simpan Transaksi?',
            text: 'Pastikan data sudah benar sebelum menyimpan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-save me-1"></i> Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection

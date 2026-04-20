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
                                <div class="mb-3" style="display: none;">
                                    <label class="form-label fw-bold">Tipe Pelanggan</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="customer_mode" id="mode_existing" value="existing" onchange="toggleCustomerMode()">
                                        <label class="form-check-label" for="mode_existing">Member Terdaftar</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="customer_mode" id="mode_new" value="new" checked onchange="toggleCustomerMode()">
                                        <label class="form-check-label" for="mode_new">Data Baru / Non-Member</label>
                                    </div>
                                </div>

                                {{-- Section: Existing Customer (HIDDEN) --}}
                                <div id="section_existing" style="display: none;">
                                    <label for="id_customer" class="form-label fw-bold">Pilih Pelanggan (Member)</label>
                                    <select name="id_customer" id="id_customer" class="form-select form-select-lg">
                                        <option value="">--Pilih Pelanggan--</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" data-orders="{{ $customer->orders_count }}">
                                                {{ $customer->customer_name }} - {{ $customer->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Section: New Customer (ACTIVE AS DEFAULT) --}}
                                <div id="section_new">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Pelanggan</label>
                                            <input type="text" name="new_customer_name" id="new_customer_name_field" class="form-control" placeholder="Nama Pelanggan" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">No. Telepon</label>
                                            <input type="number" name="new_phone" id="new_phone_field" class="form-control" placeholder="No. Telepon" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="new_address" id="new_address_field" class="form-control" rows="2" placeholder="Alamat" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-check" style="display: none;">
                                        <input class="form-check-input" type="checkbox" name="is_member_baru" id="is_member_baru" value="1" onchange="hitungGrandTotal()">
                                        <label class="form-check-label fw-bold text-success" for="is_member_baru">
                                            Daftar sebagai Member Baru (Diskon 5%)
                                        </label>                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-calendar me-1"></i>Tanggal Order
                                </label>
                                <input type="date" name="order_date" id="order_date" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-3">
                                <label for="order_end_date" class="form-label fw-bold">
                                    <i class="bi bi-calendar-check me-1"></i>Estimasi Selesai
                                </label>
                                <input type="date" name="order_end_date" id="order_end_date" class="form-control form-control-lg" required>
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
                            <table class="table table-striped align-middle" id="tableDetail">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Layanan</th>
                                        <th width="15%">Harga</th>
                                        <th width="10%">Qty (kg)</th>
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
                                            <input type="number" name="qty[]" class="form-control input-qty" min="0.1" step="0.1" value="1" required>
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
                                    <tr class="table-light">
                                        <td colspan="4" class="text-end fw-bold">SUBTOTAL:</td>
                                        <td colspan="2" class="fw-bold" id="subtotalDisplay">Rp 0</td>
                                        <td></td>
                                    </tr>
                                    <tr class="table-light" style="display: none;">
                                        <td colspan="4" class="text-end fw-bold">PAJAK (10%):</td>
                                        <td colspan="2" class="fw-bold" id="taxDisplay">Rp 0</td>
                                        <td></td>
                                    </tr>
                                    <tr class="table-info" style="display: none;">
                                        <td colspan="4" class="text-end fw-bold text-primary">DISKON (<span id="discountPercentDisplay">0</span>%):</td>
                                        <td colspan="2" class="fw-bold text-primary" id="discountDisplay"> Rp 0</td>
                                        <td></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td colspan="4" class="text-end fw-bold fs-5">GRAND TOTAL:</td>
                                        <td colspan="2" class="fw-bold fs-5" id="grandTotalDisplay">Rp 0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Payment Section --}}
                        <div class="row mt-4 justify-content-end">
                            <div class="col-md-4" style="display: none;">
                                <div class="mb-3">
                                    <label for="voucher_code" class="form-label fw-bold">Kode Voucher (Diskon 10%)</label>
                                    <div class="input-group">
                                        <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="Masukkan kode voucher">
                                        <button type="button" class="btn btn-outline-primary" id="btnCekVoucher">Cek</button>
                                    </div>
                                    <small class="text-muted">Gunakan <b>LAUNDRY10</b> untuk diskon tambahan.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body pt-3">
                                        <div class="mb-3">
                                            <label for="order_pay" class="form-label fw-bold">Uang Bayar (Pay)</label>
                                            <input type="number" name="order_pay" id="order_pay" class="form-control form-control-lg text-end" value="0" placeholder="0">
                                        </div>
                                        <div class="mb-0">
                                            <label for="order_change" class="form-label fw-bold">Kembalian (Change)</label>
                                            <input type="text" name="order_change_display" id="order_change_display" class="form-control form-control-lg text-end fw-bold text-success" value="Rp 0" readonly>
                                            <input type="hidden" name="order_change" id="order_change" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const qty = parseFloat(inputQty.value) || 0;
        const subtotal = price * qty;

        inputHarga.value = formatRupiah(price);
        inputSubtotal.value = formatRupiah(subtotal);

        hitungGrandTotal();
    }

    // Recalculate grand total
    function hitungGrandTotal() {
        let subtotal = 0;
        document.querySelectorAll('.row-detail').forEach(function(row) {
            const selectService = row.querySelector('.select-service');
            const inputQty = row.querySelector('.input-qty');
            const selectedOption = selectService.options[selectService.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const qty = parseFloat(inputQty.value) || 0;
            subtotal += price * qty;
        });

        // Logic Discount (DISABLED FOR CLEAN VERSION)
        let discountPercent = 0;
        const discountAmount = 0;
        const tax = 0;
        const grandTotal = subtotal;

        document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
        document.getElementById('discountPercentDisplay').textContent = discountPercent;
        document.getElementById('discountDisplay').textContent = formatRupiah(discountAmount);
        document.getElementById('taxDisplay').textContent = formatRupiah(tax);
        
        const gtDisplay = document.getElementById('grandTotalDisplay');
        gtDisplay.textContent = formatRupiah(grandTotal);
        gtDisplay.setAttribute('data-value', grandTotal); // Simpan nilai asli tanpa format di sini

        hitungKembalian(); // Update change as well
    }

    // Toggle mode antara pelanggan lama (existing) dan baru (new)
    function toggleCustomerMode() {
        const mode = document.querySelector('input[name="customer_mode"]:checked').value;
        const sectionExisting = document.getElementById('section_existing');
        const sectionNew = document.getElementById('section_new');
        
        if (mode === 'existing') {
            sectionExisting.style.display = 'block';
            sectionNew.style.display = 'none';
            // Reset fields new customer if switched back
            document.getElementById('new_customer_name_field').value = '';
            document.getElementById('new_phone_field').value = '';
            document.getElementById('new_address_field').value = '';
            document.getElementById('is_member_baru').checked = false;
        } else {
            sectionExisting.style.display = 'none';
            sectionNew.style.display = 'block';
            // Reset dropdown existing if switched
            document.getElementById('id_customer').value = '';
        }
        hitungGrandTotal();
    }

    // Trigger hitung ketika ganti customer (dropdown)
    document.getElementById('id_customer').addEventListener('change', hitungGrandTotal);
    document.getElementById('btnCekVoucher').addEventListener('click', function() {
        const voucherInput = document.getElementById('voucher_code').value;
        if (voucherInput === 'LAUNDRY10') {
            Swal.fire({
                icon: 'success',
                title: 'Voucher Berhasil!',
                text: 'Potongan harga 10% diaktifkan.',
                timer: 1500,
                showConfirmButton: false
            });
        } else if (voucherInput !== "") {
            Swal.fire({
                icon: 'error',
                title: 'Voucher Tidak Valid',
                text: 'Kode voucher yang Anda masukkan salah.',
            });
        }
        hitungGrandTotal();
    });

    // Calculate change
    function hitungKembalian() {
        const grandTotal = parseFloat(document.getElementById('grandTotalDisplay').getAttribute('data-value')) || 0;
        const bayar = parseInt(document.getElementById('order_pay').value) || 0;
        const kembalian = bayar - grandTotal;

        const displayElement = document.getElementById('order_change_display');
        displayElement.value = formatRupiah(kembalian > 0 ? kembalian : 0);
        document.getElementById('order_change').value = kembalian > 0 ? kembalian : 0;

        // Visual cue if payment is insufficient
        if (bayar < grandTotal && bayar > 0) {
            displayElement.classList.remove('text-success');
            displayElement.classList.add('text-danger');
            displayElement.value = "Kurang " + formatRupiah(Math.abs(kembalian));
        } else {
            displayElement.classList.remove('text-danger');
            displayElement.classList.add('text-success');
        }
    }

    document.getElementById('order_pay').addEventListener('input', hitungKembalian);

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
                <input type="number" name="qty[]" class="form-control input-qty" min="0.1" step="0.1" value="1" required>
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

        // Validasi: Semua baris layanan harus dipilih
        let allServicesSelected = true;
        const selectServices = document.querySelectorAll('.select-service');
        
        if (selectServices.length === 0) {
            Swal.fire({ icon: 'error', title: 'Oops!', text: 'Tambahkan minimal 1 layanan.' });
            return;
        }

        selectServices.forEach(function(select) {
            if (!select.value || select.value === "") {
                allServicesSelected = false;
                select.classList.add('is-invalid');
            } else {
                select.classList.remove('is-invalid');
            }
        });

        if (!allServicesSelected) {
            Swal.fire({
                icon: 'error',
                title: 'Data Belum Lengkap',
                text: 'Pastikan semua baris layanan sudah dipilih layanannya.',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        const grandTotalText = document.getElementById('grandTotalDisplay').textContent;
        const grandTotal = parseInt(grandTotalText.replace(/[^0-9]/g, '')) || 0;
        const bayar = parseInt(document.getElementById('order_pay').value) || 0;

        // Uang bayar kurang diizinkan, jadi pengecekan ini dihapus atau hanya jadi peringatan santai (opsional)
        // Kita langsung lanjut ke konfirmasi simpan

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
                // Disable button and show loading to prevent double submit
                const btnSubmit = document.querySelector('button[type="submit"]');
                if (btnSubmit) {
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
                }
                
                form.submit();
            }
        });
    });

    // Handle Quick Customer Creation
</script>
@endsection

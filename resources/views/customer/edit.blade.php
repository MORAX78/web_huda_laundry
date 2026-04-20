@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Pelanggan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Edit Pelanggan</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <h5 class="card-title">Edit Customer</h5>

                @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Nama Customer</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Masukkan nama customer" value="{{ old('customer_name', $customer->customer_name) }}">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor HP" value="{{ old('phone', $customer->phone) }}">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Masukkan alamat" value="{{ old('address', $customer->address) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
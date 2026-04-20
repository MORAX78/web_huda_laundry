@extends('layouts.app')
@section('content')
<div class="pagetitle">
    <h1>Tambah Pelanggan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Data Pelanggan</a></li>
            <li class="breadcrumb-item active">Tambah Pelanggan</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <h5 class="card-title">{{ $title ?? '' }}</h5>
                <form action="{{ route('customers.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Masukkan nama pelanggan" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">No. HP</label>
                    <input type="number" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor hp" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Masukkan alamat" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

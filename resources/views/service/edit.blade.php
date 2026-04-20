@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit Jenis Service</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Edit Jenis Service</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <h5 class="card-title">Edit Jenis Service</h5>

                @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('services.update', $service->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Nama Jasa</label>
                        <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Masukkan nama jasa" value="{{ old('service_name', $service->service_name) }}">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga (kg)</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Masukkan harga" value="{{ old('price', $service->price) }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Masukkan deskripsi" value="{{ old('description', $service->description) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary">Kembali</a>

                </form> 

            </div>
        </div>
    </div>
</div>
@endsection
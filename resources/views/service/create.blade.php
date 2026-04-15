    @extends('layouts.app')
    @section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <h5 class="card-title">{{ $title ?? '' }}</h5>
                    <form action="{{ route('services.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Nama Jasa</label>
                        <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Masukkan nama jasa" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Masukkan harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Masukkan deskripsi">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                </form>
                </div>
            </div>
        </div>
    </div>
    @endsection

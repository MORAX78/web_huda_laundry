@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Data Jasa</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Data Jasa</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <h5 class="card-title">Data Jasa</h5>

                <div class="mb-3 text-end">
                    <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">
                        Tambah Jenis Jasa
                    </a>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Jasa</th>
                            <th>Harga (kg)</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $service)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $service->service_name }}</td>
                            <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                            <td>{{ $service->description }}</td>
                            <td>
                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Data Kosong</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection
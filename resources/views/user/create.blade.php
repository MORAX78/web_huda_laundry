@extends('layouts.app')
@section('content')
<div class="pagetitle">
    <h1>Tambah User</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Data User</a></li>
            <li class="breadcrumb-item active">Tambah User</li>
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
                <form action="{{ route('users.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Level</label>
                    <select name="id_level" id="" class="form-select">
                        <option value="" disabled selected>--Pilih Level--</option>
                        @foreach ($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->level_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

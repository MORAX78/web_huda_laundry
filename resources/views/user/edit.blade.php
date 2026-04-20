@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Edit User</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Edit User</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <h5 class="card-title">Edit User</h5>

                @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Level --}}
                    <div class="mb-3">
                        <label for="id_level" class="form-label">Level</label>
                        <select name="id_level" id="id_level" class="form-select">
                            <option value="" disabled>--Pilih Level--</option>
                            @foreach ($levels as $level)
                            <option value="{{ $level->id }}"
                                {{ old('id_level', $user->id_level) == $level->id ? 'selected' : '' }}>
                                {{ $level->level_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama" value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak diubah">
                        <small class="text-secondary">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
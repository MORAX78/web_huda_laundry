@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Home</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <p>Selamat datang, {{ auth()->user()->name }}! </p>
    <p>Level: {{ auth()->user()->level->level_name }}</p>
</section>
@endsection

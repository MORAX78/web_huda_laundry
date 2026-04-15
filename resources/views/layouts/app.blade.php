<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Laundry</title>

    {{-- CSS NiceAdmin --}}
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

    {{-- Header/Navbar --}}
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
                <span class="d-none d-lg-block">Sistem Laundry</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <span class="d-none d-md-block ps-2">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    {{-- Sidebar --}}
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @php $level = auth()->user()->level->level_name; @endphp

            @if($level == 'Administrator')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people"></i><span>Master Data</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="master-nav" class="nav-content collapse">
                    <li>
                        <a href="{{ route('users.index') }}">
                            <i class="bi bi-circle"></i><span>Data User</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customers.index') }}">
                            <i class="bi bi-circle"></i><span>Data Customer</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('services.index') }}">
                            <i class="bi bi-circle"></i><span>Jenis Service</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if($level == 'Operator' || $level == 'Administrator')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('transaksi.index') }}">
                    <i class="bi bi-cart"></i>
                    <span>Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('pickup.index') }}">
                    <i class="bi bi-bag-check"></i>
                    <span>Pickup</span>
                </a>
            </li>
            @endif

            @if($level == 'Pimpinan')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('laporan.index') }}">
                    <i class="bi bi-bar-chart"></i>
                    <span>Laporan</span>
                </a>
            </li>
            @endif

        </ul>
    </aside>

    {{-- Main Content --}}
    <main id="main" class="main">
        @yield('content')
    </main>

    {{-- JS NiceAdmin --}}
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        let form = this.closest('form');

        Swal.fire({
            title: 'Are you sure want to delete?',
            text: "Data cannot be restored!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
    @if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif
</body>
</html>
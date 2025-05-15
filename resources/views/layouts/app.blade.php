<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ config('app.name', 'Ticketing System') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <style>
            body {
                background: url('{{ asset('assets/images/gambar.jpg') }}') no-repeat center center fixed;
                background-size: cover;
                background-color: #7B887F;
            }

            .scrolling-navbar {
                overflow-x: auto;
                white-space: nowrap;
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .scrolling-navbar::-webkit-scrollbar {
                display: none;
            }

            .nav-link.active {
                font-weight: bold;
                border-bottom: 2px solid #fff;
            }
        </style>
    </head>

    <body>
        <div style="display: flex; flex-direction: column; min-height: 100vh">
            <!-- Navbar Lapisan 1 -->
            <nav class="navbar navbar-expand-lg navbar-light border-bottom" style="background-color: #7B887F">
                <div class="container d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a class="navbar-brand me-3" href="/">
                            <img src="/assets/images/logo.png" alt="Logo" width="40" height="40" />
                            <strong
                                class="ms-2 d-none d-md-inline text-white">{{ config('app.name', 'Ticketing System') }}</strong>
                        </a>
                    </div>
                    <div class="d-flex align-items-center">
                        @auth
                            <div class="dropdown d-flex align-items-center">

                                <button class="btn dropdown-toggle d-flex align-items-center gap-2 border-0" type="button"
                                    style="background-color: #7B887F; outline: none; box-shadow: none; color: white;"
                                    onmouseover="this.style.backgroundColor='#96A691';"
                                    onmouseout="this.style.backgroundColor='#7B887F';"
                                    onfocus="this.style.outline='none'; this.style.boxShadow='none';"
                                    data-bs-toggle="dropdown">

                                    <img src="/assets/images/user.png" alt="Avatar" width="40" height="40"
                                        class="rounded-circle me-2" />

                                    <div class="text-start d-flex flex-column gap-0">
                                        <strong class="d-block" style="margin-top: -4px">{{ auth()->user()->name }}</strong>
                                        <small style="margin-top: -4px">{{ auth()->user()->role }}</small>
                                    </div>

                                    <!-- Icon dropdown putih -->
                                    <i class="bi bi-chevron-down text-white"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('password.edit') }}">Ubah Password</a></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Navbar Lapisan 2 -->
            <div class="border-bottom text-white" style="background-color: #96A691">
                <div class="container">
                    <div class="scrolling-navbar d-flex py-2">
                        @auth
                            @if (auth()->user()->isAdmin())
                                <a class="nav-link px-3 {{ request()->routeIs('admin.tickets.index') ? 'active' : '' }}"
                                    href="{{ route('admin.tickets.index') }}">Daftar Aduan</a>
                                <a class="nav-link px-3 {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                                    href="{{ route('admin.users.index') }}">Kelola Pengguna</a>
                                <a class="nav-link px-3 {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}"
                                    href="{{ route('admin.reports.index') }}">Cetak Laporan</a>
                            @else
                                <a class="nav-link px-3 {{ request()->routeIs('tickets.index') ? 'active' : '' }}"
                                    href="{{ route('tickets.index') }}">Daftar Aduan</a>
                                <a class="nav-link px-3 {{ request()->routeIs('tickets.create') ? 'active' : '' }}"
                                    href="{{ route('tickets.create') }}">Tambah Aduan</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Konten --}}
            <div style="flex-grow: 1; display: flex; flex-direction: column">
                @yield('content')
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>

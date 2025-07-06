<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ config('app.name', 'Ticketing System') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


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

                                <div class="me-3">
                                    <a href="https://wa.me/6282313650125" target="_blank"
                                        class="btn border-0 bg-transparent text-white position-relative"
                                        title="Hubungi Customer Service">
                                        <i class="bi bi-telephone" style="font-size: 1.5rem;"></i>
                                    </a>
                                </div>


                                <div class="me-3 position-relative">
                                    <div class="dropdown me-3 position-relative">
                                        <button id="notifBtn"
                                            class="btn border-0 bg-transparent text-white position-relative dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-bell" style="font-size: 1.5rem;"></i>
                                            <span id="notifCount"
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                                style="font-size: 0.6rem; display: none;">0</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="width: 300px;"
                                            id="notifList">
                                            <li class="dropdown-header">Notifikasi</li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li class="text-center text-muted"><small>Memuat...</small></li>
                                        </ul>
                                    </div>

                                </div>


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
        <script>
            const USER_ROLE = "{{ auth()->user()->role }}";
            async function fetchNotifications() {
                try {
                    const res = await fetch(`{{ route('notifications.index') }}`);
                    const data = await res.json();

                    const badge = document.getElementById('notifCount');
                    const list = document.getElementById('notifList');

                    // Update badge
                    if (data.length > 0) {
                        badge.textContent = data.length;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }

                    // Build dropdown list
                    list.innerHTML = '';
                    list.innerHTML += '<li class="dropdown-header">Notifikasi</li>';
                    list.innerHTML += '<li><hr class="dropdown-divider"></li>';

                    if (data.length === 0) {
                        list.innerHTML +=
                            '<li class="text-center text-muted"><small>Tidak ada notifikasi baru</small></li>';
                    } else {
                        data.forEach(notif => {
                            let ticketUrl = USER_ROLE === 'admin' ?
                                `/admin/tickets/${notif.ticket_id}` :
                                `/tickets/${notif.ticket_id}`;

                            list.innerHTML += `
                                <li class="px-3 py-2 text-wrap small border-bottom">
                                    <div class="d-flex">
                                        <i class="bi bi-info-circle text-primary me-2"></i>
                                        <div>
                                            <p class="fw-medium mb-2">${notif.message}</p>
                                            <div class="text-muted text-start" style="font-size: 0.7rem;">
                                                ${new Date(notif.created_at).toLocaleString()}
                                        </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-end">
                                        <a href="${ticketUrl}" class="btn btn-sm btn-outline-primary">Lihat Tiket</a>
                                    </div>
                                </li>
                            `;
                        });


                        list.innerHTML += '<li><hr class="dropdown-divider"></li>';
                        list.innerHTML +=
                            '<li class="text-center"><a href="#" class="text-primary small" id="markReadLink">Tandai sudah dibaca</a></li>';
                    }
                } catch (e) {
                    console.error("Gagal mengambil notifikasi:", e);
                }
            }

            setInterval(fetchNotifications, 5000);
            document.addEventListener("DOMContentLoaded", fetchNotifications);

            document.addEventListener("click", function(e) {
                if (e.target.id === 'markReadLink') {
                    e.preventDefault();
                    fetch(`{{ route('notifications.read') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(() => {
                        document.getElementById('notifCount').style.display = 'none';
                        fetchNotifications(); // refresh isi list
                    });
                }
            });
        </script>


    </body>

</html>

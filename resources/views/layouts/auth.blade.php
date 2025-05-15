<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Aplikasi')</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <style>
            html,
            body {
                height: 100%;
                margin: 0;
                padding: 0;
                background: url('{{ asset('assets/images/gambar.jpg') }}') no-repeat center center fixed;
                background-size: cover;
            }

            .overlay {
                background: rgba(0, 0, 0, 0.4);
                /* Efek gelap transparan */
                height: 100%;
                width: 100%;
                position: absolute;
                top: 0;
                left: 0;
            }

            .content-wrapper {
                position: relative;
                z-index: 1;
            }
        </style>
        @stack('styles')
    </head>

    <body>
        <div class="overlay"></div> <!-- Layer transparan -->

        <div class="d-flex flex-column min-vh-100 content-wrapper">
            <!-- Konten Tengah -->
            <div class="d-flex flex-column flex-grow-1">
                @yield('content')
            </div>

            <!-- Footer tetap di dalam min-vh-100 -->
            <footer class="text-center text-light py-3">
                &copy; {{ date('Y') }} Perusahaan Anda. All Rights Reserved.
            </footer>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>

</html>

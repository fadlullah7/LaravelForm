<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pendaftaran Siswa')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body>

    @if(session('success'))
        <script>
            window._flashMessage = "{{ session('success') }}";
        </script>
    @endif

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')

    {{-- Tampilkan flash message --}}
    <script>
        if (window._flashMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: window._flashMessage,
                timer: 2000,
                showConfirmButton: false
            });
        }
    </script>
</body>
</html>

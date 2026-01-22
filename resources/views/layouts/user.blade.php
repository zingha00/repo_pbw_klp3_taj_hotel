<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <script src="https://unpkg.com/feather-icons"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Grand Azure Hotel')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    @stack('styles')
</head>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>


<body class="min-h-screen flex flex-col bg-gray-50 text-gray-800">

    @include('components.toast')

    {{-- NAVBAR (HILANG DI HALAMAN AUTH) --}}
    @if (!Request::routeIs('login', 'register', 'password.request'))
        @include('components.user-navbar')
    @endif`

    <main class="
            flex-grow
            @if (!Request::routeIs('login', 'register', 'password.request'))
                pt-0
            @endif
        ">
        @yield('content')
    </main>`

    {{-- FOOTER (OPSIONAL: JUGA BISA DISAMAKAN) --}}
    @if (!Request::routeIs('login', 'register', 'password.request'))
        @include('components.footer')
    @endif

    @stack('modals')

    <script src="{{ asset('assets/js/global.js') }}"></script>
    <script>
        feather.replace();
    </script>

    @stack('scripts')
</body>

</html>
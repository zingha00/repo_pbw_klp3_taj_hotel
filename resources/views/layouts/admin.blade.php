<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/admin/base.css') }}">

    @yield('css')
</head>


<body class="bg-slate-100">

    <div class="flex h-screen">

        {{-- SIDEBAR --}}
        @include('components.admin-sidebar')

        <main class="flex-1 flex flex-col">

            {{-- HEADER --}}
            @include('components.admin-header')

            {{-- CONTENT --}}
            <div class="p-6 overflow-y-auto">
                @yield('content')
            </div>

        </main>

    </div>
    @stack('scripts')
    @yield('js')
</body>


</html>
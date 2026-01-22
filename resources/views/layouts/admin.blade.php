<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
@stack('styles')
</head>
<body>

@yield('content')

<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>feather.replace()</script>
@stack('scripts')
</body>
</html>

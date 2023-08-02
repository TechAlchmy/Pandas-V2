<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="@yield('description')">
<meta name="keywords" content="@yield('keywords')">

<title>@yield('title','Panda')</title>

<!-- Fonts -->


<!-- Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
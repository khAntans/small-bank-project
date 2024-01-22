<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    {{--fonts--}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-screen">
<div class="grid grid-cols-2 auto-rows-auto w-[600px] gap-2 h-min justify-center align-middle m-auto pb-[50px]">

    <img src="{{ asset('/logo.svg') }}" class="h-[200px] w-auto col-start-1 col-end-3 row-start-1 row-end-2 block justify-self-center" alt="Logo of BBBBank">
    <a href="{{ route('login') }}"
       class="h-min text-xl text-right pr-6 col-start-1 col-end-2 row-start-2 row-end-3 font-semibold text-gray-500 hover:text-gray-900 focus:text-red-500">
        Log in</a>

    <a href="{{ route('register') }}"
       class="h-min text-xl text-left pl-3 col-start-2 col-end-3  row-start-2 row-end-3 font-semibold text-gray-500 hover:text-gray-900 focus:text-red-500">Register</a>



</div>
</body>
</html>

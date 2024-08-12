<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    {{-- JS & CSS --}}
    {{-- load different scripts based on the route --}}
    @if (Route::currentRouteName() === 'stundenplan.show_doz' || Route::currentRouteName() === 'stundenplan.my' ||Route::currentRouteName() === 'stundenplan.show_sem')
        @vite(['resources/js/timetable_show.js', 'resources/css/timetable_show.css'])
    @elseif (Route::currentRouteName() === 'stundenplan.edit')
        @vite(['resources/js/timetable_crud.js', 'resources/css/timetable_crud.css'])
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    {{-- Page Heading --}}
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Page Content --}}
    <main>
        {{ $slot }}
    </main>
</div>
</body>
</html>

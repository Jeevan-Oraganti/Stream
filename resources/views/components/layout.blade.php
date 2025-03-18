<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
{{--    <link ref="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">--}}

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <script src="https://cdn.tailwindcss.com/"></script>--}}
    <script defer src="{{ asset('js/alpine.js') }}"></script>

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/js/app.js'])

</head>

<body>
    <script>
        window.Laravel = {
            isLoggedIn: @json(auth()->check()),
            user: @json(auth()->user())
        };
    </script>

    <div id="app" class="bg-gradient-to-r from-gray-800 via-gray-700 to-gray-600">
        <notice></notice>
        @include('layouts.header')
        <main>
            {{ $slot }}
        </main>
        @include('layouts.footer')
    </div>
</body>

</html>

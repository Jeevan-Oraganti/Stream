<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
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
    <div id="app" class="bg-white">
        <admin-notice :notices-json="{{ json_encode($notices) }}"></admin-notice>
    </div>
</body>

</html>

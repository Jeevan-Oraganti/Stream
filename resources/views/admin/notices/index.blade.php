<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="{{ asset('css/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script defer src="{{ asset('js/alpine.js') }}"></script>

    {{-- <script src="https://cdn.tailwindcss.com/"></script>--}}

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
        <notices-list
            :user="{{ json_encode(auth()->user()) }}">
        </notices-list>
    </div>
</body>

</html>
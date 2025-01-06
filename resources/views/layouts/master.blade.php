<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com/"></script>

    <title>My App</title>

    @vite('resources/js/app.js')

</head>

<body>
    <div id="app" class="bg-gradient-to-r from-gray-800 via-gray-700 to-gray-600">
        @include('layouts.header')

        <section class="section">
            <div class="container">

                <router-view></router-view>

            </div>
        </section>

        @include('layouts.tabs')

        @include('layouts.footer')

    </div>
</body>

</html>

<x-layout>
    <div id="app" class="bg-gradient-to-r from-gray-800 via-gray-700 to-gray-600">
        @include('layouts.header')
        <notice></notice>
        <section class="section">
            <div class="container">

                <router-view></router-view>

            </div>
        </section>
    </div>
</x-layout>

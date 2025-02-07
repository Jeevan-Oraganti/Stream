<x-layout>
    @section('content')
    <section class="hero text-white is-small" style="justify-content: center;">
        <div class="hero-body flex justify-center items-center">
            <div class="container">
                <carousel :autoplay="true">
                    <img src="https://images.unsplash.com/photo-1736024759853-97fbaef8ac62?q=80&w=1974&auto=format&fit=crop" class="w-96 h-64 mx-auto rounded-lg shadow-lg">
                    <img src="https://images.unsplash.com/photo-1736024852276-112dad90586d?q=80&w=1974&auto=format&fit=crop" class="w-96 h-64 mx-auto rounded-lg shadow-lg">
                    <img src="https://images.pexels.com/photos/3941855/pexels-photo-3941855.jpeg?auto=compress&cs=tinysrgb" class="w-96 h-64 mx-auto rounded-lg shadow-lg">
                </carousel>
            </div>
        </div>

        <div class="hero-foot py-8">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-one-third">
                        <div class="bg-white p-6 rounded-lg shadow-xl text-center">
                            <h2 class="text-4xl font-bold mb-2 text-blue-600">
                                <count :to="3485"></count>
                            </h2>
                            <p class="text-lg font-medium text-gray-600">Streams happening per Day</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
    <section class="section">
        <div class="container">
            <router-view></router-view>
        </div>
    </section>
</x-layout>
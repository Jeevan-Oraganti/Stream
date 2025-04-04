@include('layouts.navbar')
<section class="hero text-white is-small"
    style="justify-content: center;">

    <!-- Hero content: Centered Carousel -->
    <div class="hero-body flex justify-center items-center">
        <div class="container">
            <carousel :autoplay="true">
                <img
                    src="https://images.unsplash.com/photo-1736024759853-97fbaef8ac62?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="w-96 h-64 mx-auto rounded-lg shadow-lg">
                <img
                    src="https://images.unsplash.com/photo-1736024852276-112dad90586d?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="w-96 h-64 mx-auto rounded-lg shadow-lg">
                <img
                    src="https://images.pexels.com/photos/3941855/pexels-photo-3941855.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                    class="w-96 h-64 mx-auto rounded-lg shadow-lg">
                <img
                    src="https://images.unsplash.com/photo-1732871706369-73c5e4d27e38?q=80&w=1925&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="w-96 h-64 mx-auto rounded-lg shadow-lg">
                <img
                    src="https://images.unsplash.com/photo-1735597693189-9ba81b5bbc83?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="w-96 h-64 mx-auto rounded-lg shadow-lg">
            </carousel>
        </div>
    </div>

    <!-- Hero foot: Statistics Section -->
    <div class="hero-foot py-8">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-one-third">
                    <div class="bg-white p-6 rounded-lg shadow-xl text-center">
                        <h2 class="text-4xl font-bold mb-2 text-blue-600">
                            <span data-tooltip-name="count">
                                <count :to="3485"></count>
                            </span>
                        </h2>
                        <p class="text-lg font-medium text-gray-600">Streams happening per Day</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <tooltippy name="count" placement="top">
        <h1 class="font-bold text-lg">3485</h1>
        <p class="text-xs text-gray-500">Streams per day</p>
    </tooltippy>

    <!-- Pinned Footer Navigation -->
    <!-- <pinned-to-top> -->
    <div ref="menu-banner-meta" class="hero-foot">
        <nav class="tabs is-boxed is-fullwidth">
            <div class="container">
                <ul class="flex justify-center space-x-6">
                    <router-link to="/" exact v-slot="{ href, navigate, isActive, isExactActive }" custom>
                        <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                            <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                                :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-900': !isActive }">Home</a>
                        </li>
                    </router-link>
                    <router-link to="/about" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                        <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                            <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                                :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-900': !isActive }">About</a>
                        </li>
                    </router-link>
                    <router-link to="/faq" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                        <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                            <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                                :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-900': !isActive }">FAQ</a>
                        </li>
                    </router-link>
                    <router-link to="/contact" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                        <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                            <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                                :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-900': !isActive }">Contact</a>
                        </li>
                    </router-link>
                    <router-link to="/tabs" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                        <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                            <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                                :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-900': !isActive }">Tabs</a>
                        </li>
                    </router-link>
                    @admin
                    <router-link to="/dashboard" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                        <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                            <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                                :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-900': !isActive }">Admin Dashboard</a>
                        </li>
                    </router-link>
                    @endadmin
                </ul>
            </div>
        </nav>
    </div>
    <!-- </pinned-to-top> -->
</section>
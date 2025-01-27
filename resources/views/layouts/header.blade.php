<section class="hero text-white is-small"
    style="background: conic-gradient(from 300deg, #3d52a0, #7091e6, #ede8f5); justify-content: center;">
    <!-- Hero head: Navigation -->
    <!-- <dark-mode-toggle></dark-mode-toggle> -->
    <div class="hero-head">
        <nav class="navbar">
            <div class="container flex justify-between items-center">
                <div class="navbar-brand flex items-center">
                    <dropdown class="relative">
                        <template v-slot:trigger>
                            <button
                                class="text-white font-bold uppercase flex items-center transition-transform duration-300 ease-in-out hover:scale-105 space-x-1 border border-gray-500 px-4 py-2 rounded-lg" style="background: linear-gradient(45deg, #3d52a0, #7091e6, #ede8f5);">
                                <span>My</span>
                                <span>Stream</span>
                            </button>
                        </template>


                        <div class=" absolute bg-white text-gray-700 rounded shadow-lg mt-2 ml-6">
                            <a href="/" class="block text-sm px-4 py-2 hover:bg-gray-200 rounded-md">Home</a>
                            <a href="#/about" class="block text-sm px-4 py-2 hover:bg-gray-200 rounded-md">About</a>
                            <a href="#/faq" class="block text-sm px-4 py-2 hover:bg-gray-200 rounded-md">FAQ</a>
                            <a href="#/contact" class="block text-sm px-4 py-2 hover:bg-gray-200 rounded-md">Contact</a>
                        </div>
                    </dropdown>
                </div>

                <div id="navbarMenuHeroA" class="navbar-menu flex items-center mt-4">
                    <div class="navbar-end flex space-x-4">
                        <catalog-dropdown></catalog-dropdown>
                        <series-dropdown></series-dropdown>
                        <podcasts-dropdown></podcasts-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <portal-target name="nav-catalog"></portal-target>
        <portal-target name="nav-series"></portal-target>
        <portal-target name="nav-podcasts"></portal-target>
    </div>

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
                        <router-link to="/analytics" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                            <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                                <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                                    :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-900': !isActive }">Analytics</a>
                            </li>
                        </router-link>
                    </ul>
                </div>
            </nav>
        </div>
    <!-- </pinned-to-top> -->
</section>
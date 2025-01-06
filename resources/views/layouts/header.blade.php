<section class="hero bg-gradient-to-r from-gray-800 via-gray-700 to-gray-600 text-white is-small">
    <!-- Hero head: Navigation -->
    <div class="hero-head">
        <nav class="navbar">
            <div class="container flex justify-between items-center">
                <div class="navbar-brand flex items-center">
                    <dropdown class="relative">
                        <template v-slot:trigger>
                            <button class="text-white font-bold uppercase flex items-center transition-transform duration-300 ease-in-out hover:scale-105 space-x-1">
                                <span>My</span>
                                <span>Stream</span>
                            </button>
                        </template>

                        <div class="absolute bg-white text-gray-700 rounded shadow-lg">
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
        <div class="container text-center">
            <carousel :autoplay="true">
                <img src="https://placehold.co/600x400/orange/white" alt="Christmas" class="w-96 h-auto mx-auto rounded-lg shadow-lg">
                <img src="https://placehold.co/600x400/green/white" alt="Christmas" class="w-96 h-auto mx-auto rounded-lg shadow-lg">
                <img src="https://placehold.co/600x400/black/white" alt="Christmas" class="w-96 h-auto mx-auto rounded-lg shadow-lg">
                <img src="https://placehold.co/600x400/red/white" alt="Christmas" class="w-96 h-auto mx-auto rounded-lg shadow-lg">
                <img src="https://placehold.co/600x400/white/black" alt="Christmas" class="w-96 h-auto mx-auto rounded-lg shadow-lg">
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
    <pinned-to-top>
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
                                    :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-700': !isActive }">About</a>
                            </li>
                        </router-link>
                        <router-link to="/faq" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                            <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                                <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                                    :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-700': !isActive }">FAQ</a>
                            </li>
                        </router-link>
                        <router-link to="/contact" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                            <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                                <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                                    :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-700': !isActive }">Contact</a>
                            </li>
                        </router-link>
                    </ul>
                </div>
            </nav>
        </div>
    </pinned-to-top>
</section>

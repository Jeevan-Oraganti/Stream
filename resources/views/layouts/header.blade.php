<section class="hero is-primary is-medium">
    <!-- Hero head: will stick at the top -->
    <div class="hero-head">
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <a class="navbar-item">
                        My Stream
                    </a>
                    <span class="navbar-burger" data-target="navbarMenuHeroA">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>
                <div id="navbarMenuHeroA" class="navbar-menu">
                    <div class="navbar-end">
                        <div class="mt-4 md:flex md:justify-center text-center md:w-1/2 md:mx-auto">
                            <catalog-dropdown></catalog-dropdown>
                            <series-dropdown></series-dropdown>
                            <podcasts-dropdown></podcasts-dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <portal-target name="nav-catalog"></portal-target>
        <portal-target name="nav-series"></portal-target>
        <portal-target name="nav-podcasts"></portal-target>

    </div>

    <!-- Hero content: will be in the middle -->
    <div class="hero-body">
        <div class="container has-text-centered">
            <carousel :autoplay="true">
                <img src="https://placehold.co/600x400/orange/white" alt="Christmas"
                     style="width: 500px; height: auto;">
                <img src="https://placehold.co/600x400/green/white" alt="Christmas" style="width: 500px; height: auto;">
                <img src="https://placehold.co/600x400/black/white" alt="Christmas" style="width: 500px; height: auto;">
                <img src="https://placehold.co/600x400/red/white" alt="Christmas" style="width: 500px; height: auto;">
                <img src="https://placehold.co/600x400/white/black" alt="Christmas" style="width: 500px; height: auto;">
            </carousel>
        </div>
    </div>

    <!-- Hero footer: will stick at the top when scrolled -->
    {{--    <menu-view></menu-view>--}}
    <pinned-to-top>
        <div ref="menu-banner-meta" class="hero-foot">
            <nav class="tabs is-boxed is-fullwidth">
                <div class="container mx-auto">
                    <ul class="flex justify-center space-x-4">
                        <router-link to="/" exact v-slot="{ href, navigate, isActive, isExactActive }" custom>
                            <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                                <a :href="href" @click="navigate" class="block py-2 px-4"
                                   :class="{ 'text-blue-500': isActive, 'text-gray-700': !isActive }">Home</a>
                            </li>
                        </router-link>
                        <router-link to="/about" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                            <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                                <a :href="href" @click="navigate" class="block py-2 px-4"
                                   :class="{ 'text-blue-500': isActive, 'text-gray-700': !isActive }">About</a>
                            </li>
                        </router-link>
                        <router-link to="/faq" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                            <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                                <a :href="href" @click="navigate" class="block py-2 px-4"
                                   :class="{ 'text-blue-500': isActive, 'text-gray-700': !isActive }">FAQ</a>
                            </li>
                        </router-link>
                        <router-link to="/contact" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                            <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                                <a :href="href" @click="navigate" class="block py-2 px-4"
                                   :class="{ 'text-blue-500': isActive, 'text-gray-700': !isActive }">Contact</a>
                            </li>
                        </router-link>
                    </ul>
                </div>
            </nav>
        </div>
    </pinned-to-top>

</section>

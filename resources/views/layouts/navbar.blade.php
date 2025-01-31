<nav class="bg-gray-800 text-white py-4">
    <div class="container mx-auto flex justify-between items-center">
        <a class="text-2xl font-bold" href="#">My Stream</a>
        <button class="md:hidden text-white focus:outline-none" @click="menuOpen = !menuOpen">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div :class="{'block': menuOpen, 'hidden': !menuOpen}" class="w-full md:flex md:items-center md:w-auto">
            <ul class="md:flex md:space-x-6">
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
                <router-link to="/tabs" v-slot="{ href, navigate, isActive, isExactActive }" custom>
                    <li :class="{ 'is-active': isActive, 'exact-active': isExactActive }" class="relative">
                        <a :href="href" @click="navigate" class="block py-2 px-4 rounded-md"
                            :class="{ 'bg-blue-500 text-white': isActive, 'bg-gray-200 text-gray-900': !isActive }">Tabs</a>
                    </li>
                </router-link>
            </ul>
        </div>
    </div>
</nav>

<script>
    export default {
        data() {
            return {
                menuOpen: false
            };
        }
    };
</script>

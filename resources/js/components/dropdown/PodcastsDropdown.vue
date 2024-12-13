<template>
    <div @mouseover="active = true" @mouseout="active = false" class="relative">

        <a href="/podcasts"
           class="block md:px-8 md:flex-1 uppercase text-transparent-50 font-bold hover:text-white pb-8">
            Podcasts
        </a>

        <portal to="nav-podcasts">
            <div v-show="active"
                 @mouseover="active = true"
                 @mouseout="active = false"
                 class="absolute w-full z-10 bg-gray-800 text-white shadow-lg transform transition-all ease-in-out duration-300"
                 style="left: 0; min-height: 400px;">

                <div class="container mx-auto py-8 px-4">

                    <div class="space-y-8">

                        <div>
                            <h4 class="text-lg font-bold mb-3">Web Development</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="/podcasts/web-dev/1" class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">The Web Dev Show</span>
                                        <span class="block text-sm text-gray-400">Stay updated with the latest web dev trends and tips.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/podcasts/web-dev/2" class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">JavaScript Weekly</span>
                                        <span class="block text-sm text-gray-400">A podcast about the latest JavaScript news and tutorials.</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-lg font-bold mb-3">Technology</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="/podcasts/tech/1" class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">Tech Today</span>
                                        <span class="block text-sm text-gray-400">A podcast that covers the latest in technology and gadgets.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/podcasts/tech/2" class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">The Future of Tech</span>
                                        <span class="block text-sm text-gray-400">Explore the future of technology and its impact on society.</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </portal>

    </div>
</template>

<script>
export default {
    data() {
        return {
            active: false
        };
    }
};
</script>

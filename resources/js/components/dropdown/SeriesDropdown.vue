<template>
    <div @mouseover="active = true" @mouseout="active = false" class="relative">

        <a href="/series"
           class="block md:px-8 md:flex-1 uppercase text-transparent-50 font-bold hover:text-white pb-8">
            Series
        </a>

        <portal to="nav-series">
            <div v-show="active"
                 @mouseover="active = true"
                 @mouseout="active = false"
                 class="absolute w-full z-10 bg-gray-800 text-white shadow-lg transform transition-all ease-in-out duration-300"
                 style="left: 0; min-height: 400px;">

                <div class="container mx-auto py-8">

                    <div class="space-y-8">
                        <div>
                            <h4 class="text-lg font-bold mb-3">Action Series</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="/series/action/1" class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">Series 1: Action Unleashed</span>
                                        <span class="block text-sm text-gray-400">Explore epic battles and intense missions!</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/series/action/2" class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">Series 2: The Last Hero</span>
                                        <span class="block text-sm text-gray-400">A lone hero fights for survival in a post-apocalyptic world.</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-lg font-bold mb-3">Drama Series</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="/series/drama/1" class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">Series 3: The Love Story</span>
                                        <span class="block text-sm text-gray-400">A heartwarming tale of love and loss.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/series/drama/2" class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">Series 4: Family Ties</span>
                                        <span class="block text-sm text-gray-400">Follow the emotional journey of a complicated family.</span>
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

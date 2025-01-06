<template>
    <div @mouseover="active = true" @mouseout="active = false" class="relative">

        <a href="/catalog"
           class="block md:px-8 md:flex-1 uppercase text-gray-300 font-bold transition-transform duration-300 ease-in-out hover:text-white">
            Catalog
        </a>

        <portal to="nav-catalog">
            <div v-show="active"
                 @mouseover="active = true"
                 @mouseout="active = false"
                 class="absolute w-full z-10 bg-gray-800 text-white shadow-lg transform transition-all ease-in-out duration-300"
                 style="left: 0; min-height: 400px;">
                <div class="container mx-auto py-8 px-4">

                    <div class="space-y-8">
                        <div>
                            <h4 class="text-lg font-bold mb-3">Courses</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="/catalog/course/1"
                                       class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">Introduction to Web Development</span>
                                        <span class="block text-sm text-gray-400">Learn the basics of web development with HTML, CSS, and JavaScript.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/catalog/course/2"
                                       class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">Advanced JavaScript</span>
                                        <span class="block text-sm text-gray-400">Deep dive into modern JavaScript features and practices.</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-lg font-bold mb-3">Podcasts</h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="/catalog/podcast/1"
                                       class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">The Web Dev Show</span>
                                        <span class="block text-sm text-gray-400">Stay up-to-date with the latest trends in web development.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/catalog/podcast/2"
                                       class="block hover:bg-gray-700 px-4 py-2 rounded-md transition-all">
                                        <span class="font-semibold">JavaScript Weekly</span>
                                        <span class="block text-sm text-gray-400">A podcast about the latest JavaScript news and tutorials.</span>
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

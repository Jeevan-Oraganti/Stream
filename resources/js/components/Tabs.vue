<template>
    <div>
        <LoadingBar :progress="progress"/>

        <div class="p-8 bg-white rounded-xl">
            <div class="border border-gray-400 p-5 rounded-xl">
                <h1 class="text-xl font-semibold mb-8 text-gray-500">Explore Categories</h1>
                <div class="mb-4">
                    <div class="flex items-center mb-4">
                        <div class="relative w-full">
                            <input type="text" v-model="TabSearchQuery" placeholder="Search..."
                                   class="w-full p-2 text-sm rounded-lg bg-gray-200 text-black border border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   @input="debouncedSearchTabs"/>
                            <span v-if="loading" class="loader absolute right-3 top-2 items-center"></span>

                            <span v-if="!loading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                     viewBox="0 0 24 26" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                          d="M10 2a9 9 0 100 18 9 9 0 000-18zM23 21l-5-5"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div v-show="check" class="flex flex-col justify-center mt-2 mb-8 text-sm">
                        <h1 class="text-red-500">No matching content found.</h1>
                    </div>
                </div>

                <div>
                    <div class="hidden sm:block">
                        <div class="border-b border-gray-500">
                            <nav class="-mb-px flex space-x-8 justify-around" aria-label="Tabs">
                                <a v-for="(tab, index) in tabs" :key="index" @click.prevent="selectTab(tab)"
                                   :class="[tab === activeTab ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-700 hover:text-gray-700', 'whitespace-nowrap border-b-2 px-1 py-2 text-sm font-medium']"
                                   :aria-current="tab === activeTab ? 'page' : undefined">
                                    {{ tab.title }}
                                    <span :class="tab.content ? 'dot-green' : 'dot-red'"></span>
                                </a>
                            </nav>
                        </div>
                    </div>

                    <div class="sm:hidden">
                        <div class="relative">
                            <select @change="selectTab(tabs[$event.target.selectedIndex])"
                                    class="w-full py-2 pl-3 pr-8 text-base text-gray-900 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option v-for="(tab, index) in tabs" :key="index" :selected="tab === activeTab">
                                    {{ tab.title }}
                                </option>
                            </select>
                        </div>
                    </div>

                </div>

                <div v-for="tab in tabs" :key="tab.slug">
                    <tab :tab="tab" @tab-selected="handleTabSelected" @stack-length="stackSize"
                         @stack-length-zero="stackZero" :ref="'tab-' + tab.slug"
                         v-show="tab === activeTab"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Tab from './Tab.vue';
import LoadingBar from './LoadingBar.vue'
import axios from 'axios';
import {debounce} from 'lodash';
import GlobalLoadingBar from "../utilities/GlobalLoadingBar.js";

export default {
    components: {
        Tab,
        LoadingBar,
    },
    data() {
        return {
            tabs: [
                {title: 'Electronics', slug: 'electronics', content: null},
                {title: 'Clothing', slug: 'clothing', content: null},
                {title: 'Books', slug: 'books', content: null},
                {title: 'Movies', slug: 'movies', content: null},
                {title: 'Travel', slug: 'travel', content: null},
                {title: 'Food', slug: 'food', content: null},
                {title: 'Fitness', slug: 'fitness', content: null},
                {title: 'Gaming', slug: 'gaming', content: null},
            ],
            activeTab: null,
            TabSearchQuery: '',
            check: false,
            loading: false,
            controller: null,
            matchedTab: null,
            matchFound: false,
            progress: 0,
            interval: null,
            loadingStack: [],
        };
    },
    methods: {
        stackSize() {
            const stackLength = GlobalLoadingBar.getLoadingStackLength();

            console.log(stackLength);

            if (!this.interval && stackLength >= 0) {
                this.interval = setInterval(() => {
                    if (this.progress < 95) {
                        this.progress += 5;
                    }
                }, 100);
            }
        },
        stackZero() {
            clearInterval(this.interval);
            this.interval = null;
            this.progress = 100;

            setTimeout(() => {
                this.loading = false;
                this.progress = 0;
            }, 500);
        },

        selectTab(tab) {
            if (this.controller) {
                this.controller.abort();
            }

            this.activeTab = tab;

            if (!tab.content) {
                this.$refs[`tab-${tab.slug}`][0].loadTabContent();
            }

            this.check = false;
        },

        handleTabSelected({content}) {
            this.activeTab.content = content;
            this.$forceUpdate();
        },
        preloadTabs() {
            const preloadSlugs = ['electronics', 'movies'];
            preloadSlugs.forEach(slug => {
                const tab = this.tabs.find(t => t.slug === slug);
                if (tab && !tab.content) {
                    axios.get(`/tabs/${tab.slug}/content`)
                        .then(response => {
                            tab.content = response.data;
                        })
                        .catch((error) => {
                            console.log(`Failed to preload content for ${slug}`, error);
                        });
                }
            });
        },

        async searchTitles(query) {
            let matchedTab = null;
            let matchFound = false;

            for (const tab of this.tabs) {
                if (tab.title.toLowerCase().includes(query)) {
                    matchedTab = tab;
                    matchFound = true;
                    this.selectTab(matchedTab);
                    break;
                }
            }

            return {matchedTab, matchFound}
        },

        async searchContent(query) {
            let matchedTab = null;
            let matchFound = false;

            for (const tab of this.tabs) {
                if (tab.content && JSON.stringify(tab.content).toLowerCase().includes(query)) {
                    matchedTab = tab;
                    matchFound = true;
                    this.selectTab(matchedTab);
                    break;
                }
            }

            return {matchedTab, matchFound}
        },

        async loadContentBySlug(tab) {
            if (!tab.content) {
                try {
                    const response = await axios.get(`/tabs/${tab.slug}/content`, {
                        signal: this.controller.signal,
                    });
                    tab.content = response.data;
                    console.log(`Content loaded for ${tab.slug}`);
                } catch (error) {
                    if (axios.isCancel(error)) {
                        console.log(`Request canceled for ${tab.slug}`);
                    } else {
                        console.error(`Failed to load content for ${tab.slug}`, error);
                    }
                }
            }
        },

        async searchTabs() {
            this.loading = true;
            this.check = false;
            const query = this.TabSearchQuery.toLowerCase();
            let matchedTab = null;
            let matchFound = false;

            if (this.controller) {
                this.controller.abort();
            }

            this.controller = new AbortController();

            if (query === '') {
                this.controller.abort();
                this.activeTab = this.tabs[0];
                this.selectTab(this.tabs[0]);
                this.check = false;
                this.loading = false;
                return;
            }

            if (!this.interval) {
                this.interval = setInterval(() => {
                    if (this.progress < 95) {
                        this.progress += 5;
                    }
                }, 100);
            }

            const searchByTitle = await this.searchTitles(query);
            matchedTab = searchByTitle.matchedTab;
            matchFound = searchByTitle.matchFound;

            if (!matchFound) {
                const processTab = async (tab) => {
                    if (matchFound) return;

                    await this.loadContentBySlug(tab);

                    const searchByContent = await this.searchContent(query);
                    matchedTab = searchByContent.matchedTab;
                    matchFound = searchByContent.matchFound;

                };

                for (const tab of this.tabs) {
                    await processTab(tab);
                    if (matchFound) {
                        break;
                    }
                }
            }

            if (!matchFound) {
                console.log('No matching tab found.');
                this.check = true;
            }

            clearInterval(this.interval);
            this.interval = null;
            this.progress = 100;

            setTimeout(() => {
                this.loading = false;
                this.progress = 0;
            }, 500);

            this.$forceUpdate();
        },
        debouncedSearchTabs: debounce(function () {
            this.searchTabs();
        }, 500),

    },

    mounted() {
        if (this.tabs.length > 0) {
            this.selectTab(this.tabs[0]);
            this.preloadTabs();
        }
    },

    watch: {
        activeTab(newTab) {
            document.title = 'Category - ' + newTab.title;
        }
    },

};
</script>

<style scoped>
.dot-red {
    height: 6px;
    width: 6px;
    background: linear-gradient(135deg, #ff4d4d, #ff0000);
    border-radius: 50%;
    display: inline-block;
    margin-left: 8px;
}

.dot-green {
    height: 6px;
    width: 6px;
    background: linear-gradient(135deg, #4caf50, #2e7d32);
    border-radius: 50%;
    display: inline-block;
    margin-left: 8px;
}

.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 25px;
    height: 25px;
    border: 4px solid rgba(255, 255, 255, 0.2);
    border-top-color: rgba(37, 197, 239, 1);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>

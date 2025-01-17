<template>
    <div>
        <div class="mb-4">
            <div class="flex items-center">
                <input type="text" v-model="TabSearchQuery" placeholder="Search..."
                    class="w-full mb-2 p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    @input="searchTabs1" />
                <span v-if="loading" class="loader absolute right-20 mb-2"></span>
            </div>


            <div v-show="check" class="flex flex-col justify-center mt-2 mb-8 text-sm">
                <h1 class="text-red-500">No matching content found.</h1>
                <!--<p class="text-yellow-500">Try adjusting your search to find what you're looking for.</p>-->
            </div>
        </div>

        <ul class="flex flex-wrap mb-4 border-b border-gray-400 justify-around" role="tablist">
            <li v-for="(tab, index) in tabs" :key="index" class="cursor-pointer" :class="{
                'border border-b-0 rounded-t-lg bg-white shadow-md': tab === activeTab,
                'hover:bg-gray-200 hover:rounded-t-lg hover:text-black': tab !== activeTab,
            }" :style="tab === activeTab ? 'margin-bottom: -1px' : ''" role="presentation">
                <button :class="{
                    'text-black font-bold': tab === activeTab,
                    'hover:text-black': tab !== activeTab,
                }" class="focus:outline-none px-4 py-2 text-gray-300" role="tab" :aria-selected="tab === activeTab"
                    @click="selectTab(tab)">
                    {{ tab.title }}
                    <span :class="tab.content ? 'dot-green' : 'dot-red'" class="ml-2"></span>
                </button>
            </li>
        </ul>

        <div v-for="tab in tabs" :key="tab.slug">
            <tab :tab="tab" @tab-selected="handleTabSelected" :ref="'tab-' + tab.slug" v-show="tab === activeTab" />
        </div>
    </div>
</template>

<script>
import Tab from './Tab.vue';
import axios from 'axios';

export default {
    components: {
        Tab,
    },
    props: {
        tabs: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            activeTab: null,
            TabSearchQuery: '',
            initialTabs: ['electronics', 'movies'],
            check: false,
            loading: false,
        };
    },
    methods: {
        selectTab(tab) {
            this.activeTab = tab;
            if (!tab.content) {
                this.$refs[`tab-${tab.slug}`][0].loadTabContent();
            }
            this.check = false;
        },
        handleTabSelected({ content }) {
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
                            // this.$refs[tab-${tab.slug}][0].setContent(response.data);
                        })
                        .catch((error) => {
                            console.log('Failed to preload content for ${ slug }, error');
                        });
                }
            });
        },
        async searchTabs1() {
            this.loading = true;
            this.check = false;
            const query = this.TabSearchQuery.toLowerCase();
            let matchedTab = null;
            let matchFound = false;


            if (query === '') {
                this.activeTab = this.tabs[0];
                this.check = false;
                this.loading = false;
                return;
            }

            const processTab = async (tab) => {
                if (matchFound) return;

                if (!tab.content) {
                    try {
                        const response = await axios.get(`/tabs/${tab.slug}/content`);
                        tab.content = response.data;
                    } catch (error) {
                        console.error(`Failed to load content for ${tab.slug}`, error);
                    }
                }

                if (
                    tab.title.toLowerCase().includes(query) ||
                    (tab.content && JSON.stringify(tab.content).toLowerCase().includes(query))
                ) {
                    matchedTab = tab;
                    matchFound = true;
                    this.selectTab(matchedTab);
                }
            };

            for (const tab of this.tabs) {
                await processTab(tab);
                if (matchFound) break;
            }

            if (!matchedTab) {
                console.log('No matching tab found.');
                this.check = true;
            }

            // await Promise.all(this.tabs.map(tab =>
            //     tab.content ? Promise.resolve() : axios
            //         .get(`/tabs/${tab.slug}/content`)
            //         .then(response => tab.content = response.data)));

            this.loading = false;
            this.$forceUpdate();
        },

        async searchTabs2() {
            this.loading = true;
            const query = this.TabSearchQuery.toLowerCase();

            if (query === '') {
                this.activeTab = this.tabs[0];
                this.check = false;
                this.loading = false;
                return;
            }

            const contentLoadPromises = this.tabs.map(async (tab) => {
                if (!tab.content) {
                    try {
                        const response = await axios.get(`/tabs/${tab.slug}/content`);
                        tab.content = response.data;
                    } catch (error) {
                        console.error('Failed to load content for ${ tab.slug }', error);
                    }
                }
                return tab;
            });


            let matchedTab = this.tabs.find(tab => tab.title.toLowerCase().includes(query));

            if (!matchedTab) {
                matchedTab = this.tabs.find(tab =>
                    tab.content && JSON.stringify(tab.content).toLowerCase().includes(query)
                );
            }

            if (matchedTab) {
                this.selectTab(matchedTab);
            }
            if (!matchedTab && this.tabs.every(tab => tab.content)) {
                console.log('No matching tab found.');
                this.check = true;
                this.activeTab = this.tabs[0];
            }

            await Promise.all(contentLoadPromises);

            this.loading = false;
            this.$forceUpdate();
        },
    },
    mounted() {
        if (this.tabs.length > 0) {
            this.selectTab(this.tabs[3]);
            this.selectTab(this.tabs[0]);
            this.preloadTabs();
        }
    },
};
</script>

<style scoped>
.dot-red {
    height: 10px;
    width: 10px;
    background: linear-gradient(135deg, #ff4d4d, #ff0000);
    border-radius: 50%;
    display: inline-block;
    margin-left: 8px;
    box-shadow: 0 0 8px rgba(255, 77, 77, 0.6), 0 0 3px rgba(255, 0, 0, 0.4);
    animation: pulse-red 2s infinite;
}

.dot-green {
    height: 10px;
    width: 10px;
    background: linear-gradient(135deg, #4caf50, #2e7d32);
    border-radius: 50%;
    display: inline-block;
    margin-left: 8px;
    box-shadow: 0 0 8px rgba(76, 175, 80, 0.6), 0 0 3px rgba(0, 255, 0, 0.4);
    animation: pulse-green 2s infinite;
}

.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 30px;
    height: 30px;
    border: 4px solid rgba(255, 255, 255, 0.2);
    border-top-color: #4caf50;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

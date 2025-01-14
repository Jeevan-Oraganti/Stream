<template>
    <div>
        <!-- Tab Navigation -->
        <ul class="flex flex-wrap mb-4 border-b border-gray-400 justify-around" role="tablist">
            <li v-for="(tab, index) in tabs" :key="index" class="cursor-pointer" :class="{
                'border border-b-0 rounded-t-lg bg-white shadow-md': tab === activeTab,
                'hover:bg-gray-200 hover:rounded-t-lg hover:text-black': tab !== activeTab
            }" :style="tab === activeTab ? 'margin-bottom: -1px' : ''" role="presentation">
                <button :class="{ 'text-black font-bold': tab === activeTab, 'hover:text-black': tab !== activeTab }"
                    class="focus:outline-none px-4 py-2 text-gray-300" role="tab" :aria-selected="tab === activeTab"
                    @click="selectTab(tab)">
                    {{ tab.title }}
                    <span :class="tab.content ? 'dot-green' : 'dot-red'" class="ml-2"></span>
                </button>
            </li>
        </ul>

        <div v-for="tab in tabs" :key="tab.id">
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
        };
    },
    methods: {
        selectTab(tab) {
            this.activeTab = tab;
            if (!tab.content) {
                this.$refs[`tab-${tab.slug}`][0].loadTabContent();
            }
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
                            this.$refs[`tab-${tab.slug}`][0].setContent(response.data);
                        })
                        .catch((error) => {
                            console.log(`Failed to preload content for ${slug}`, error);
                        });
                }
            });
        }
    },
    watch: {
        activeTab(newTab) {
            document.title = `Category - ${newTab.title}`;
        }
    },
    mounted() {
        if (this.tabs.length > 0) {
            this.selectTab(this.tabs[0]);
            this.preloadTabs();
        }
    }
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
    box-shadow:
        0 0 8px rgba(255, 77, 77, 0.6), 0 0 3px rgba(255, 0, 0, 0.4);
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
</style>

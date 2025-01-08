<template>
    <div>
        <!-- Tab Navigation -->
        <ul class="flex mb-4 border-b border-gray-400 justify-around" role="tablist">
            <li v-for="(tab, index) in tabs" :key="index" class="px-4 py-2 cursor-pointer" :class="{
                'border border-b-0 rounded-t-lg bg-white shadow-md': tab === activeTab,
                'hover:bg-gray-200 hover:rounded-t-lg': tab !== activeTab
            }" :style="tab === activeTab ? 'margin-bottom: -1px' : ''" role="presentation">
                <button :class="{ 'text-black font-bold': tab === activeTab }" class="focus:outline-none text-gray-300"
                    role="tab" :aria-selected="tab === activeTab" @click="selectTab(tab)">
                    {{ tab.title }}
                    <span :class="tabCacheStatus[tab.id] ? 'dot-green' : 'dot-red'" class="ml-2"></span>
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <tab v-if="activeTab" :tab="activeTab" @tab-selected="handleTabSelected" />

    </div>
</template>

<script>
import Tab from './Tab.vue';

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
            activeTabContent: null,
            loading: false,
            tabCacheStatus: {},
        };
    },
    methods: {
        selectTab(tab) {
            this.activeTab = tab;
        },
        handleTabSelected({ content, cached }) {
            this.activeTabContent = content;
            this.tabCacheStatus[this.activeTab.id] = cached;
            console.log('Tab content:', content);
            this.$forceUpdate();
        },
    },
    mounted() {
        if (this.tabs.length > 0) {
            this.selectTab(this.tabs[0]);
        }
    }
};
</script>

<style scoped>
button {
    transition: background-color 0.2s ease;
}

.dot-red {
    height: 8px;
    width: 8px;
    background-color: red;
    border-radius: 50%;
    display: inline-block;
    margin-left: 4px;
}

.dot-green {
    height: 8px;
    width: 8px;
    background-color: green;
    border-radius: 50%;
    display: inline-block;
    margin-left: 4px;
}
</style>
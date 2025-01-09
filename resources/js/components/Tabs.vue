<template>
    <div>
        <!-- Tab Navigation -->
        <ul class="flex mb-4 border-b border-gray-400 justify-around" role="tablist">
            <li v-for="(tab, index) in tabs" :key="index" class="cursor-pointer" :class="{
                'border border-b-0 rounded-t-lg bg-white shadow-md': tab === activeTab,
                'hover:bg-gray-200 hover:rounded-t-lg hover:text-black': tab !== activeTab
            }" :style="tab === activeTab ? 'margin-bottom: -1px' : ''" role="presentation">
                <button :class="{ 'text-black font-bold': tab === activeTab, 'hover:text-black': tab !== activeTab }"
                    class="focus:outline-none px-4 py-2 text-gray-300" role="tab" :aria-selected="tab === activeTab"
                    @click="selectTab(tab)">
                    {{ tab.title }}
                    <span :class="tabCacheStatus[tab.id] ? 'dot-green' : 'dot-red'" class="ml-2"></span>
                </button>
            </li>
        </ul>

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

/* @keyframes pulse-red {

    0%,
    100% {
        box-shadow: 0 0 10px rgba(255, 77, 77, 0.6), 0 2px 4px rgba(0, 0, 0, 0.2);
        transform: scale(1);
    }

    50% {
        box-shadow: 0 0 20px rgba(255, 77, 77, 0.8), 0 4px 8px rgba(0, 0, 0, 0.2);
        transform: scale(1.2);
    }
}

@keyframes pulse-green {

    0%,
    100% {
        box-shadow: 0 0 10px rgba(76, 175, 80, 0.6), 0 2px 4px rgba(0, 0, 0, 0.2);
        transform: scale(1);
    }

    50% {
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.8), 0 4px 8px rgba(0, 0, 0, 0.2);
        transform: scale(1.2);
    }
} */
</style>

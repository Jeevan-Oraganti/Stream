<template>
    <div>
        <!-- Tab Navigation -->
        <ul class="flex mb-4 border-b border-gray-400 justify-around" role="tablist">
            <li v-for="(tab, index) in tabs" :key="index" class="px-4 py-2 cursor-pointer" :class="{
                'border border-b-0 rounded-t-lg bg-white shadow-md': tab === activeTab,
                'hover:bg-gray-200 hover:rounded-t-lg': tab !== activeTab
            }" :style="tab === activeTab ? 'margin-bottom: -1px' : ''" role="presentation">
                <button v-text="tab.title" :class="{ 'text-black font-bold': tab === activeTab }"
                    class="focus:outline-none text-gray-300" role="tab" :aria-selected="tab === activeTab"
                    @click="loadTabContent(tab)"></button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div v-if="loading" class="text-center p-4">
            <span class="text-gray-700">Loading...</span>
        </div>
        <div v-else-if="activeTabContent" class="p-4 rounded-lg shadow" role="tabpanel">
            <div>
                <h2 class="text-2xl font-bold mb-4" v-text="activeTabContent.title"></h2>
                <p class="mb-4 text-gray-100" v-text="activeTabContent.content"></p>
                <ul class="list-disc pl-6">
                    <li v-for="(item, index) in activeTabContent.items" :key="index" class="mb-2 text-gray-100">
                        {{ item }}
                    </li>
                </ul>
            </div>
        </div>
        <div v-else class="text-center text-gray-700 p-4">
            Select a category to view details.
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        tabs: {
            type: Array,
            required: true,
            validator(value) {
                return value.every((tab) => tab.title && tab.id); // Validate tab structure
            },
        },
    },
    data() {
        return {
            activeTab: null,
            activeTabContent: null,
            loading: false,
        };
    },
    methods: {
        selectTab(tab) {
            this.activeTab = tab;
            this.$emit('tab-selected', tab);
        },
        loadTabContent(tab) {
            this.activeTab = tab;
            this.activeTabContent = null;
            this.loading = true;

            // Simulate an API call to fetch the content
            axios
                .get(`/tabs/${tab.id}/content`)
                .then((response) => {
                    // Parse the JSON response
                    this.activeTabContent = response.data;
                })
                .catch(() => {
                    this.activeTabContent = {
                        title: "Error",
                        content: "Failed to load content.",
                        items: [],
                    };
                    console.log("Failed to load content.");
                })
                .finally(() => {
                    this.loading = false;
                });
                console.log(tab);
        },
    },
    mounted() {
        if (this.tabs.length > 0) {
            this.loadTabContent(this.tabs[0]);
            this.selectTab(this.tabs[0]);
        }
    },
};
</script>

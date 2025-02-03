<template>
    <div>
        <div v-if="loading" class="container loader p-2 items-centers mt-20 mb-20"></div>
        <div v-else-if="currentTab.content" class="p-4 rounded-lg" role="tab-panel">
            <div>
                <h2 class="text-sm font-bold mb-4 text-gray-700" v-html="currentTab.title"></h2>
                <p class="text-sm mb-4 text-gray-700" v-html="currentTab.content.description"></p>
                <ul class="list-disc pl-6">
                    <li v-for="(item, index) in currentTab.content.items" :key="index"
                        class="mb-2 text-gray-600 text-sm">
                        {{ item }}
                    </li>
                </ul>
            </div>
        </div>
        <div v-else class="text-center text-gray-400 p-4">
            Select a category to view details.
        </div>
    </div>
</template>

<script>
import axios from "axios";
import GlobalLoadingBar from "../utilities/GlobalLoadingBar.js";

export default {
    props: {
        tab: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            loading: false,
            currentTab: this.tab,
            controller: null,
            uniqueId: null,
        };
    },
    methods: {
        async loadTabContent() {
            if (this.tab.content) {
                this.$emit('tab-selected', {content: this.tab.content});
                return;
            }

            this.loading = true;

            if (this.controller) {
                this.controller.abort();
            }

            this.controller = new AbortController();

            try {
                this.requestId = Symbol();
                GlobalLoadingBar.addLoadingRequest(this.requestId);
                this.$emit('stack-length');

                const response = await axios.get(`/tabs/${this.tab.slug}/content`, {
                    signal: this.controller.signal,
                });

                if (this.tab === this.$parent.activeTab) {
                    this.tab.content = response.data;
                    this.$emit('tab-selected', {content: response.data});
                } else {
                    this.controller.abort();
                }

            } catch (error) {
                this.loading = false;

                if (axios.isCancel(error)) {
                    console.log(`Request for ${this.tab.slug} was canceled.`);
                } else {
                    this.tab.content = {
                        title: "Error",
                        description: `
                        <div style="color: red; font-weight: bold; text-align: center; margin-top: 20px;">
                            <span style="color: yellow;">⚠ </span> An error occurred while loading the content.
                        </div>
                    `,
                    };
                    this.$emit('tab-selected', {content: this.tab.content});
                    console.error("Failed to load content", error);
                }
            } finally {
                this.loading = false;

                GlobalLoadingBar.removeLoadingRequest(this.requestId);
                const stackLength = GlobalLoadingBar.getLoadingStackLength();

                if (stackLength === 0) {
                    this.$emit('stack-length-zero');
                }
            }
        },

        setContent(content) {
            this.tab.content = content;
            this.$emit('tab-selected', {content: content, cached: true});
        },
    },
}
</script>

<style scoped>
.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    height: 50px;
    border: 5px solid rgba(255, 255, 255, 0.2);
    border-top-color: rgba(37, 197, 239, 1);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

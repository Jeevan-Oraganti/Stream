<template>
    <div>
        <div class="loading-bar" :style="{ width: progress + '%' }" :class="{ complete: progress === 100 }"></div>

        <div v-if="loading" class="container loader p-2 items-centers mt-20 mb-20"></div>
        <div v-else-if="currentTab.content" class="p-4 rounded-lg" role="tabpanel">
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
            progress: 0,
        };
    },
    methods: {
        async loadTabContent() {
            if (this.tab.content) {
                this.$emit('tab-selected', { content: this.tab.content });
                return;
            }

            this.loading = true;

            if (this.controller) {
                this.controller.abort();
            }

            this.controller = new AbortController();

            try {
                this.progress = 0;

                const interval = setInterval(() => {
                    if (this.progress < 95) {
                        this.progress += 5;
                    }
                }, 100);

                const response = await axios.get(`/tabs/${this.tab.slug}/content`, {
                    signal: this.controller.signal,
                    responseType: 'json',
                    onDownloadProgress: (progressEvent) => {
                        if (progressEvent.total > 0) {
                            this.progress = Math.floor((progressEvent.loaded / progressEvent.total) * 100);
                        }
                    }
                });

                if (this.tab === this.$parent.activeTab) {
                    this.tab.content = response.data;
                    this.$emit('tab-selected', { content: response.data });
                } else {
                    this.controller.abort();
                }

                clearInterval(interval);
                this.progress = 100;

                setTimeout(() => {
                    this.loading = false;
                }, 500);

            } catch (error) {
                clearInterval(interval);
                this.loading = false;
                this.progress = 0;

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
                    this.$emit('tab-selected', { content: this.tab.content });
                    console.error("Failed to load content", error);
                }
            } finally {
                this.loading = false;
            }
        },

        setContent(content) {
            this.tab.content = content;
            this.$emit('tab-selected', { content: content, cached: true });
        }
    }
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
    border-top-color: #3490dc;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.loading-bar {
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    width: 0;
    background: rgb(57,166,255);
    background: linear-gradient(90deg, rgba(50,137,240,1) 0%, rgba(50,137,240,1) 50%, rgba(37,197,239,1) 100%);
    transition: width 0.3s ease, opacity 0.3s ease;
    z-index: 11;
    opacity: 1;
}

.loading-bar.complete {
    box-shadow: 0 0 10px rgba(50,137,240,1), 0 0 10px rgba(37,197,239,1);
    opacity: 0;
    transition: opacity 0.5s ease;
}

</style>

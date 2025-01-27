<template>
    <div>
        <div v-if="loading" class="loading-bar" :style="{ width: progress + '%' }"></div>

        <div v-if="!currentTab.content || loading" class="container loader p-2 items-centers mt-20 mb-20"></div>
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

                this.loading = true;
                this.progress = 0;

                const interval = setInterval(() => {
                    if (this.progress < 95) {
                        this.progress++;
                    }
                }, 50);

                const response = await axios.get(`/tabs/${this.tab.slug}/content`, {
                    signal: this.controller.signal,
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
                    this.progress = 0;
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

<style>
.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    height: 50px;
    border: 5px solid rgba(255, 255, 255, 0.2);
    border-top-color: #4caf50;
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
    height: 4px;
    background: linear-gradient(90deg,
            rgba(76, 175, 80, 1) 25%,
            rgba(255, 255, 255, 0.8) 50%,
            rgba(76, 175, 80, 1) 75%);
    background-size: 200% 100%;
    z-index: 1000;
    transition: width 0.2s ease, background-position 0.5s ease;
    animation: shine 2s linear infinite;
}

@keyframes shine {
    from {
        background-position: 200% 0;
    }

    to {
        background-position: 0 0;
    }
}


@keyframes loading-bar {
    0% {
        left: -100%;
    }

    50% {
        left: 0;
    }

    100% {
        left: 100%;
    }
}
</style>

<template>
    <div>
        <div v-if="loading" class="container loader p-2 items-centers mt-20 mb-20"></div>
        <div v-else-if="tab.content" class="p-4 rounded-lg" role="tabpanel">
            <div>
                <h2 class="text-2xl font-bold mb-4 text-white" v-html="tab.content.title"></h2>
                <p class="mb-4 text-white" v-html="tab.content.content"></p>
                <ul class="list-disc pl-6">
                    <li v-for="(item, index) in tab.content.items" :key="index" class="mb-2 text-white">
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
        };
    },
    methods: {
        loadTabContent() {
            if (this.tab.content) {
                this.$emit('tab-selected', { content: this.tab.content.content });
            } else {
                this.loading = true;
                axios
                    .get(`/tabs/${this.tab.slug}/content`)
                    .then((response) => {
                        this.tab.content = response.data;
                        this.$emit('tab-selected', { content: response.data });
                    })
                    .catch(() => {
                        this.tab.content = {
                            title: "Error",
                            content: `<div style="color: red; font-weight: bold; text-align: center; margin-top: 20px;">
                                <span style="color: yellow;">⚠ </span>
                                </div>
                                <p style="color: #ff6b6b; text-align: center; margin-top: 10px;">
                                    An error occurred while loading the content. Please try again later.
                                    </p>`,
                        };
                        this.$emit('tab-selected', { content: this.tab.content });
                        console.log("Failed to load Content");
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    },
};
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
</style>

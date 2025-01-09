<template>
    <div>
        <div v-if="loading" class="container loader p-2 items-centers mt-20 mb-20">
<!--            <span class="text-white">Loading...</span>-->
        </div>
        <div v-else-if="tabContent" class="p-4 rounded-lg" role="tabpanel">
            <div>
                <!-- <h2 class="text-2xl font-bold mb-4 text-white" v-text="tabContent.title"></h2> -->
                <p class="mb-4 text-white" v-text="tabContent.content"></p>
                <ul class="list-disc pl-6">
                    <li v-for="(item, index) in tabContent.items" :key="index" class="mb-2 text-white">
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
            tabContent: null,
            loading: false,
            contentCache: {},
        };
    },
    methods: {
        loadTabContent() {
            if (this.contentCache[this.tab.id]) {
                this.tabContent = this.contentCache[this.tab.id];
                this.$emit('tab-selected', { content: this.tabContent, cached: true});
            } else {
                this.loading = true;
                axios
                    .get(`/tabs/${this.tab.slug}/content`)
                    .then((response) => {
                        this.contentCache[this.tab.id] = response.data;
                        this.tabContent = response.data;
                        this.$emit('tab-selected', { content: response.data, cached: true });
                    })
                    .catch(() => {
                        this.tabContent = {
                            title: "Error",
                            content: "Failed to load content.",
                            items: [],
                        };
                        this.$emit('tab-selected', { content: this.tabContent, cached: false });
                        console.log("Failed to load content.");
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },
    },
    mounted() {
        this.loadTabContent();
    },
    watch: {
        tab: {
            handler() {
                this.loadTabContent();
            },
            immediate: true,
        },
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

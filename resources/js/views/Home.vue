<template>
    <div>
        <div id="home" class="container mx-auto py-8 px-4 bg-gray-900 text-white rounded-lg">
            <h1 class="text-2xl text-center font-semibold mb-6 mt-4" title="Hard coding">

                <span data-tooltip-name="Welcome">Welcome to the </span>
                <span data-tooltip-name="stream">Stream </span>

                <span data-tooltip-name="products">Products</span>

                <tooltippy name="Welcome" placement="top">
                    <h1 class="text-xl font-bold">Welcome</h1>
                    <p class="text-xs">Welcome to the Stream</p>
                </tooltippy>

                <tooltippy name="stream" placement="top">
                    <h1 class="text-xl font-bold">Stream</h1>
                    <p class="text-xs">Stream is a platform for building scalable news feeds and activity streams.</p>
                </tooltippy>

                <tooltippy name="products" placement="bottom">
                    <h1 class="text-xl font-bold">Products</h1>
                    <p class="text-xs">Products are the items or services that are offered by Stream.</p>
                </tooltippy>
            </h1>

            <div class="columns is-centered">
                <div class="column is-8">

                    <div class="mb-4">
                        <input type="text" v-model="searchQuery" placeholder="Search for a status..."
                            class="w-full p-4 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="mb-8">
                        <div v-if="filteredStatuses.length > 0">
                            <div v-for="status in filteredStatuses" :key="status.id"
                                class="transition-transform duration-300 hover:scale-105 border border-gray-500 p-4 rounded-lg mb-4">
                                <div v-if="status.user" class="flex justify-between items-center mb-3">
                                    <p class="text-lg font-medium">{{ status.user.name }} said...</p>
                                    <p class="text-sm text-gray-400">{{ status.created_at | ago | capitalize }}</p>
                                </div>
                                <div v-else class="flex justify-between items-center mb-3">
                                    <p class="text-lg font-medium">Anonymous said...</p>
                                    <p class="text-sm text-gray-400">{{ status.created_at | ago | capitalize }}</p>
                                </div>
                                <div class="text-gray-300 text-base" v-text="status.body"></div>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-lg font-medium">No related Status update.</p>
                        </div>
                    </div>

                    <add-to-stream @completed="addStatus"></add-to-stream>
                </div>
            </div>


            <conditional-render when-hidden="#push-to-stream">
                <div class="participate-button fixed bottom-10 right-6 z-50">
                    <a class="bg-blue-500 text-white text-xs hover:bg-white hover:text-blue-500 rounded-full w-36 h-12 text-center flex items-center justify-center shadow-lg"
                        @click="scrollToPush">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add to Stream
                    </a>
                </div>
            </conditional-render>

        </div>
    </div>
</template>


<script>
import moment from 'moment';
import Status from '../models/Status';
import Notice from "@/components/Notice.vue";
import AddToStream from "../components/AddToStream.vue";
import Tabs from "../components/Tabs.vue";
import DarkModeToggle from '../components/DarkModeToggle.vue';

export default {
    components: { AddToStream, Tabs, DarkModeToggle, Notice },
    data() {
        return {
            statuses: [],
            searchQuery: '',
            notices: [],
        }
    },
    computed: {
        filteredStatuses() {
            if (this.searchQuery) {
                return this.statuses.filter(status =>
                    status.body.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }
            return this.statuses;
        }
    },
    filters: {
        ago(date) {
            return moment(date).fromNow();
        },
        capitalize(value) {
            return value.charAt(0).toUpperCase() + value.slice(1);
        }
    },
    created() {
        Status.all(statuses => this.statuses = statuses);
    },
    methods: {
        addStatus(status) {
            this.statuses.unshift(status);

            alert('Your status has been added to the stream!');

            window.scrollTo(0, 0);
        },
        scrollToPush() {
            const element = document.getElementById('push-to-stream');
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                })
            }
            this.$forceUpdate();
        },
    },
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
</style>

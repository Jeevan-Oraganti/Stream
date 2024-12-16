<template>
    <div id="home" class="container mx-auto py-8 px-4 bg-gray-900 text-white rounded-lg">
        <h1 class="text-2xl text-center font-semibold mb-6 mt-4" title="Hardcoding">

            <span data-tooltip-name="Welcome">Welcome to the </span>
            <span data-tooltip-name="stream">Stream </span>

            <span data-tooltip-name="products">Products</span>

            <tooltippy name="Welcome" placement="top">
                <h1>Welcome</h1>
                <p class="text-xs">Welcome to the Stream</p>
            </tooltippy>

            <tooltippy name="stream" placement="top">
                <h1>Stream</h1>
                <p class="text-xs">Stream is a platform for building scalable news feeds and activity streams.</p>
            </tooltippy>

            <tooltippy name="products" placement="bottom">
                <h1>Products</h1>
                <p class="text-xs">Products are the items or services that are offered by Stream.</p>
            </tooltippy>

        </h1>
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="mb-8">
                    <div v-for="status in statuses" :key="status.id" class="bg-gray-800 p-4 rounded-lg mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <p class="text-lg font-medium">{{ status.user.name }} said...</p>
                            <p class="text-sm text-gray-400">{{ status.created_at | ago | capitalize }}</p>
                        </div>
                        <div class="text-gray-300 text-base" v-text="status.body"></div>
                    </div>
                </div>

                <add-to-stream @completed="addStatus"></add-to-stream>
            </div>
            <conditional-render when-hidden="#js-forum-reply-button">

                <div class="participate-button fixed z-50">
                    <a class="bg-white hover:bg-blue-700 rounded-full w-16 h-16 text-center flex items-center justify-center shadow-lg"
                       @click="$modal.show('add-reply-modal')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>
                </div>

            </conditional-render>
        </div>
    </div>
</template>


<script>
import moment from 'moment';
import Status from '../models/Status';
import AddToStream from "../components/AddToStream.vue";

export default {
    components: { AddToStream },
    mounted() {
        console.log('Home component mounted.')
    },
    data() {
        return {
            statuses: []
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
        }
    }
}
</script>

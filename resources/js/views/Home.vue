<template>
    <div class="container mx-auto py-8 px-4 bg-gray-900 text-white rounded-lg">
        <h1 class="text-xl text-center font-semibold mb-6" title="Hardcode">Welcome to the <span data-tooltip="I am using a tooltip">Stream</span></h1>

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

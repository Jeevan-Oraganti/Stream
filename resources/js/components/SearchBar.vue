<template>
    <div class="mb-4">
        <div class="flex items-center">
            <input type="text" v-model="query" placeholder="Search..."
                class="w-full mb-2 p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @input="onInput" />
            <span v-if="loading" class="loader absolute right-20 mb-2"></span>
        </div>
        <div v-show="noMatch" class="flex flex-col justify-center mt-2 mb-8 text-sm">
            <h1 class="text-red-500">No matching content found.</h1>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        tabs: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            query: '',
            loading: false,
            noMatch: false,
        };
    },
    methods: {
    }
};
</script>

<style scoped>
.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.2);
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
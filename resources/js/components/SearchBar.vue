<template>
    <div class="relative w-full">
        <input type="text" v-model="query" placeholder="Search..."
               class="w-full p-2 text-sm rounded-lg bg-gray-300 text-black border border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
               @input="onInput"/>
        <span v-if="loading" class="loader absolute right-3 top-2 items-center"></span>

        <span v-if="!loading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                 viewBox="0 0 24 26" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                      d="M10 2a9 9 0 100 18 9 9 0 000-18zM23 21l-5-5"/>
            </svg>
        </span>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            required: true,
        },
        loading: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            query: this.value,
        };
    },
    watch: {
        value(newValue) {
            this.query = newValue;
        },
    },
    methods: {
        onInput() {
            this.$emit('input', this.query);
        },
    },
};
</script>

<style scoped>
.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 25px;
    height: 25px;
    border: 4px solid rgba(255, 255, 255, 0.2);
    border-top-color: #4caf50;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>

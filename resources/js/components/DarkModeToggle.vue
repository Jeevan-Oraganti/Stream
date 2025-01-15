<template>
    <button @click="toggleDarkMode" class="p-2 bg-gray-800 text-white rounded-lg focus:outline-none">
        <span v-if="darkMode">🌙</span>
        <span v-else>☀️</span>
        </button>
</template>

<script>
export default {
    data() {
        return {
            darkMode: false
        };
    },
    methods: {
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            document.documentElement.classList.toggle('dark');
        }
    }
}
</script>
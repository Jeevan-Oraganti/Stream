<template>
    <div class="mt-4 flex justify-center items-center space-x-2">
        <button v-if="pagination.prev_page_url" @click="$emit('paginate', pagination.prev_page_url)"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
            Previous
        </button>
        <span class="px-4 py-2 bg-blue-500 text-white rounded-md">
            Page {{ pagination.current_page }} of {{ pagination.last_page }}
        </span>
        <button v-if="pagination.next_page_url" @click="$emit('paginate', pagination.next_page_url)"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
            Next
        </button>
    </div>
</template>

<script>
export default {
    props: {
        pagination: Object,
    }
};
</script>
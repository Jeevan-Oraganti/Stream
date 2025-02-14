<template>
    <div class="mt-4 flex justify-center items-center space-x-2">
        <button v-if="pagination.prev_page_url" @click="$emit('paginate', pagination.prev_page_url)"
            class="px-4 py-2 bg-blue-500 text-gray-100 rounded-l-md hover:bg-gray-400 transition rounded-md">
            < Previous
        </button>
        <span class="px-4 py-2 bg-white text-gray-700 border-t border-b mx-6">
            Page {{ pagination.current_page }} of {{ pagination.last_page }}
        </span>
        <button v-if="pagination.next_page_url" @click="$emit('paginate', pagination.next_page_url)"
            class="px-4 py-2 bg-blue-500 text-gray-100 rounded-r-md hover:bg-gray-400 transition rounded-md">
            Next >
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

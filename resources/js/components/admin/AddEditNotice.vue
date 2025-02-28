<template>
    <div class="lg:max-w-6xl mr-auto p-6 my-10 bg-white border rounded-md md:mx-auto">
        <h2 class="text-sm font-semibold mb-2 text-gray-800">Add New Notice</h2>
        <p class="text-sm text-gray-500 mb-4">You can add a new notice here.</p>
        <form @submit.prevent="addNotice" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Notice Title</label>
                <input v-model="form.data.name" type="text"
                       class="text-sm text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                       :class="{ 'border-red-500': form.hasError('name') }" @input="clearError('name')">
                <span v-if="form.hasError('name')" class="text-red-500 text-sm mt-1 block">
                        {{ form.getError('name') }}</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea v-model="form.data.description"
                          class="text-sm text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                          :class="{ 'border-red-500': form.hasError('description') }"
                          @input="clearError('description')"></textarea>
                <span v-if="form.hasError('description')" class="text-red-500 text-sm mt-1 block">
                        {{ form.getError('description') }}</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Notice Type</label>
                <select v-model="form.data.notice_type_id"
                        class="text-sm text-gray-500 w-full p-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('notice_type_id') }"
                        @change="clearError('notice_type_id')">
                    <option value="1">🟠 Announcement</option>
                    <option value="2">🔵 Information</option>
                    <option value="3">🔴 Outage</option>
                </select>
                <span v-if="form.hasError('notice_type_id')" class="text-red-500 text-sm mt-1 block">
                        {{ form.getError('notice_type_id') }}
                    </span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input v-model="form.data.expiry_date" type="datetime-local"
                       class="text-sm text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                       :class="{ 'border-red-500': form.hasError('expiry_date') }" @input="clearError('expiry_date')">
                <span v-if="form.hasError('expiry_date')" class="text-red-500 text-sm mt-1 block">
                        {{ form.getError('expiry_date') }}</span>
            </div>
            <button type="submit"
                    class="text-sm w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                {{ editingNoticeId ? "Update Notice" : "Publish Notice" }}
            </button>
        </form>
    </div>
</template>

<script>
export default {
    name: "AddEditNotice"
}
</script>

<style scoped>

</style>

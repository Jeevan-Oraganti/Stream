<template>
    <div class="flex flex-col lg:flex-row lg:space-x-12 items-start p-8">
        <div class="max-w-6xl ml-auto my-10 p-6 bg-white shadow-md rounded-md">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Past Notices</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-gray-700">Title</th>
                        <th class="border p-2 text-gray-700">Description</th>
                        <th class="border p-2 text-gray-700">Type</th>
                        <th class="border p-2 text-gray-700">Expiry Date</th>
                        <th class="border p-2 text-gray-700">Created At</th>
                        <th class="border p-2 text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="notice in notices" :key="notice.id" class="text-gray-700">
                        <td class="border p-2">{{ notice.form.data.name || 'N/A' }}</td>
                        <td class="border p-2">{{ notice.form.data.description || 'N/A' }}</td>
                        <td
                            :class="['border p-2 font-semibold', notice.form.data.notice_type && notice.form.data.notice_type.color ? 'text-' + notice.form.data.notice_type.color + '-600' : 'text-gray-600']">
                            {{ notice.form.data.notice_type && notice.form.data.notice_type.type ?
                                notice.form.data.notice_type.type.charAt(0).toUpperCase() +
                                notice.form.data.notice_type.type.slice(1) : 'Unknown' }}
                        </td>
                        <td class="border p-2">
                            <span v-if="notice.form.data.expiry_date && !isNaN(new Date(notice.form.data.expiry_date))">
                                {{ new Date(notice.form.data.expiry_date).toLocaleString() }}
                            </span>
                            <span v-else class="text-red-500">No Expiry</span>
                        </td>
                        <td class="border p-2">
                            {{ notice.form.data.created_at ? new Date(notice.form.data.created_at).toLocaleString() :
                                'Unknown' }}
                        </td>
                        <td class="border p-2">
                            <button @click="deleteNotice(notice)"
                                class="bg-red-500 text-white py-1 px-2 rounded-md hover:bg-red-600 transition duration-200">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="lg:max-w-6xl mr-auto p-6 my-10 bg-white shadow-md rounded-md md:mx-auto">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Add New Notice</h2>

            <form @submit.prevent="addNotice" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Notice Title</label>
                    <input v-model="form.data.name" type="text"
                        class="text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('name') }" @input="clearError('name')">
                    <span v-if="form.hasError('name')" class="text-red-500 text-sm mt-1 block">
                        {{ form.getError('name') }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.data.description"
                        class="text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('description') }"
                        @input="clearError('description')"></textarea>
                    <span v-if="form.hasError('description')" class="text-red-500 text-sm mt-1 block">
                        {{ form.getError('description') }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Notice Type</label>
                    <select v-model="form.data.notification_type_id"
                        class="text-gray-500 w-full p-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('notification_type_id') }"
                        @change="clearError('notification_type_id')">
                        <option value="1">🟠 Announcement</option>
                        <option value="2">🔵 Information</option>
                        <option value="3">🔴 Outage</option>
                    </select>
                    <span v-if="form.hasError('notification_type_id')" class="text-red-500 text-sm mt-1 block">
                        {{ form.getError('notification_type_id') }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                    <input v-model="form.data.expiry_date" type="datetime-local"
                        class="text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('expiry_date') }" @input="clearError('expiry_date')">
                    <span v-if="form.hasError('expiry_date')" class="text-red-500 text-sm mt-1 block">
                        {{ form.getError('expiry_date') }}</span>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                    Publish Notice
                </button>
            </form>

        </div>
    </div>
</template>

<script>
import CNoticeAdmin from "@/utilities/CNoticeAdmin.js";
import { ref } from 'vue';

export default {
    props: {
        noticesJson: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            notices: ref([]),
            form: new CNoticeAdmin().form,
            errors: {},
        };
    },
    methods: {
        async addNotice() {
            try {
                await this.form.save('/admin/notice');
                window.location.reload();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Error adding notice:', error);
                }
            }
        },
        async deleteNotice(notice) {
            if (confirm("Are you sure you want to delete this notice?")) {
                try {
                    await notice.delete();
                    window.location.reload();
                } catch (error) {
                    console.error("Error deleting notice:", error);
                }
            }
        },
        clearError(field) {
            if (this.form.hasError(field)) {
                this.$delete(this.form.errors, field);
            }
        },
    },
    mounted() {
        this.notices = this.noticesJson.map(noticeJson => {
            const notice = new CNoticeAdmin(noticeJson.id);
            notice.form.data.name = noticeJson.name;
            notice.form.data.description = noticeJson.description;
            notice.form.data.notice_type = noticeJson.notice_type;
            notice.form.data.expiry_date = noticeJson.expiry_date;
            notice.form.data.created_at = noticeJson.created_at;
            return notice;
        });
    }


};
</script>

<template>
    <div class="max-w-4xl mx-auto my-10 p-6 bg-white border rounded-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">{{
            editingNoticeId ? 'Edit Notice' : 'Add New Notice'
        }}</h2>
        <form @submit.prevent="saveNotice" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Notice Title</label>
                <input v-model="form.data.name" type="text"
                    class="text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    :class="{ 'border-red-500': form.hasError('name') }" @input="clearError('name')">
                <span v-if="form.hasError('name')" class="text-red-500 text-sm mt-1 block">{{
                    form.getError('name')
                }}</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea v-model="form.data.description"
                    class="text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    :class="{ 'border-red-500': form.hasError('description') }"
                    @input="clearError('description')"></textarea>
                <span v-if="form.hasError('description')" class="text-red-500 text-sm mt-1 block">{{
                    form.getError('description') }}</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Notice Type</label>
                <select v-model="form.data.notice_type_id"
                    class="text-gray-500 w-full p-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
                    :class="{ 'border-red-500': form.hasError('notice_type_id') }"
                    @change="clearError('notice_type_id')">
                    <option value="1">🟠 Announcement</option>
                    <option value="2">🔵 Information</option>
                    <option value="3">🔴 Outage</option>
                </select>
                <span v-if="form.hasError('notice_type_id')" class="text-red-500 text-sm mt-1 block">{{
                    form.getError('notice_type_id') }}</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input v-model="form.data.expiry_date" type="datetime-local"
                    class="text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    :class="{ 'border-red-500': form.hasError('expiry_date') }" @input="clearError('expiry_date')">
                <span v-if="form.hasError('expiry_date')" class="text-red-500 text-sm mt-1 block">{{
                    form.getError('expiry_date') }}</span>
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                {{ editingNoticeId ? 'Update Notice' : 'Publish Notice' }}
            </button>
        </form>
    </div>
</template>

<script>import CNoticesAdmin from "@/utilities/CNoticesAdmin.js";

export default {
    props: {
        notice: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            form: new CNoticesAdmin().form,
            editingNoticeId: null,
            errors: {}
        };
    },
    methods: {
        async saveNotice() {
            try {
                if (this.editingNoticeId) {
                    await this.form.update(`/admin/notice/${this.editingNoticeId}`);
                } else {
                    await this.form.save('/admin/notice');
                }
                window.location.href = '/admin/notices';
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Error saving notice:', error);
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
        if (this.notice) {
            this.form.data.name = this.notice.name;
            this.form.data.description = this.notice.description;
            this.form.data.notice_type = this.notice.notice_type;
            this.form.data.expiry_date = this.notice.expiry_date;
            this.form.data.created_at = this.notice.created_at;
            this.editingNoticeId = this.notice.id;
            if (this.form.data.expiry_date) {
                this.form.data.expiry_date = new Date(this.form.data.expiry_date).toISOString().slice(0, 16);
            }
        } else {
            const now = new Date();
            const nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
            this.form.data.expiry_date = nextWeek.toISOString().slice(0, 16);
        }
    },
};
</script>

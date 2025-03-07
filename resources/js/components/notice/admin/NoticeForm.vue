<template>
    <div class="max-w-4xl mx-auto my-10 p-6 bg-white border rounded-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">{{
            form.id ? 'Edit Notice' : 'Add New Notice'
        }}</h2>
        <div v-if="localFlashSuccess" class="notification is-success">
            {{ localFlashSuccess }}
        </div>
        <div v-if="localFlashError" class="notification is-danger">
            {{ localFlashError }}
        </div>
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
                    form.getError('description')
                }}</span>
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
                    form.getError('notice_type_id')
                }}</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input v-model="form.data.expiry_date" type="datetime-local"
                    class="text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    :class="{ 'border-red-500': form.hasError('expiry_date') }" @input="clearError('expiry_date')">
                <span v-if="form.hasError('expiry_date')" class="text-red-500 text-sm mt-1 block">{{
                    form.getError('expiry_date')
                }}</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Scheduled At</label>
                <input v-model="form.data.scheduled_at" type="datetime-local"
                    class="text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                    :class="{ 'border-red-500': form.hasError('scheduled_at') }" @input="clearError('scheduled_at')">
                <span v-if="form.hasError('scheduled_at')" class="text-red-500 text-sm mt-1 block">{{
                    form.getError('scheduled_at')
                }}</span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Priority Notice</label>
                <div class="flex items-center">
                    <label class="switch">
                        <input v-model="form.data.is_sticky" type="checkbox" @change="clearError('is_sticky')">
                        <span class="slider round"></span>
                    </label>
                    <span class="ml-2 text-gray-700">Make this a priority notice</span>
                </div>
                <span v-if="form.hasError('is_sticky')" class="text-red-500 text-sm mt-1 block">{{
                    form.getError('is_sticky')
                }}</span>
            </div>
            <button type="submit"
                class="w-full bg-indigo-500 text-white py-2 rounded-md hover:bg-indigo-600 transition duration-200">
                {{ form.id ? 'Update Notice' : 'Publish Notice' }}
            </button>
        </form>
    </div>
</template>

<script>
import CNotice from "@/components/notice/CNotice.js";
import { debounce } from "lodash";

export default {
    props: {
        notice: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            form: new CNotice().form,
            errors: {},
            localFlashSuccess: this.flashSuccess,
            localFlashError: this.flashError,
        };
    },
    methods: {
        async saveNotice() {
            try {
                if (this.form.id) {
                    await this.form.update(`/admin/notice/${this.form.id}`);
                } else {
                    await this.form.save('/admin/notice');
                }
                this.localFlashSuccess = "Notice saved successfully";

                if (this.localFlashSuccess) {
                    setTimeout(() => {
                        this.localFlashSuccess = '';
                    }, 3000);
                }
                window.location.href = '/admin/notices';

            } catch (error) {
                this.localFlashError = "Error saving the notice";
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Error saving notice:', error);
                }

                if (this.localFlashError) {
                    setTimeout(() => {
                        this.localFlashError = '';
                    }, 3000);
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
            this.form.data = new CNotice(this.notice.id, this.notice).form.data;
            this.form.id = this.notice.id;
            if (this.form.data.expiry_date) {
                this.form.data.expiry_date = new Date(this.form.data.expiry_date).toISOString().slice(0, 16);
            }
        } else {
            const now = new Date();
            const nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
            this.form.data.expiry_date = nextWeek.toISOString().slice(0, 16);
        }

        this.localFlashSuccess = this.flashSuccess;
        this.localFlashError = this.flashError;
    },
};
</script>

<style scoped>
.notification {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.25rem;
    font-size: 1rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification.is-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.notification.is-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

/* The slider */
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input:checked+.slider {
    background-color: #2196F3;
}

input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}
</style>

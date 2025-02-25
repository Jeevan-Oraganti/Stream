<template>
    <div class="flex flex-col lg:flex-row lg:space-x-12 items-start p-8">
        <LoadingBar :progress="progress" v-if="loading" />
        <div class="max-w-6xl ml-auto my-10 p-6 bg-white shadow-md rounded-md">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Past Notices</h2>
            <div v-if="localFlashSuccess" class="notification is-success">
                {{ localFlashSuccess }}
            </div>
            <div v-if="localFlashError" class="notification is-danger">
                {{ localFlashError }}
            </div>
            <div class="flex items-center mb-4">
                <div class="relative w-full">
                    <input type="text" v-model="NoticeSearchQuery" placeholder="Search..."
                        class="text-gray-800 w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    <span v-if="loading" class="loader absolute right-3 top-2 items-center"></span>
                    <span v-if="!loading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                            viewBox="0 0 24 26" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M10 2a9 9 0 100 18 9 9 0 000-18zM23 21l-5-5" />
                        </svg>
                    </span>
                </div>
            </div>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2 text-gray-700">No.</th>
                        <th class="border p-2 text-gray-700">Title</th>
                        <th class="border p-2 text-gray-700">Description</th>
                        <th class="border p-2 text-gray-700">Type</th>
                        <th class="border p-2 text-gray-700">Expiry Date</th>
                        <th class="border p-2 text-gray-700">Created At</th>
                        <th class="border p-2 text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="filteredNotices.length === 0">
                        <td class="border p-5 text-center" colspan="7">
                            <div class="flex flex-col items-center justify-center h-full">
                                <div v-if="NoticeSearchQuery">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400 mx-auto">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10 2a9 9 0 100 18 9 9 0 000-18zM23 21l-5-5" />
                                    </svg>
                                    <p class="mt-2 text-gray-500">No results found for "{{ NoticeSearchQuery }}"</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr v-for="(notice, index) in notices" :key="notice.id" class="text-gray-700">
                        <td class="border p-2">{{ (localPagination.current_page - 1) * localPagination.per_page + index
                            + 1 }}</td>
                        <td class="border p-2">{{ notice.form.data.name }}</td>
                        <td class="border p-2">{{ notice.form.data.description }}</td>
                        <td
                            :class="['border p-2 font-semibold', notice.form.data.notice_type && notice.form.data.notice_type.color ? 'text-' + notice.form.data.notice_type.color + '-600' : 'text-gray-600']">
                            {{ notice.form.data.notice_type && notice.form.data.notice_type.type ?
                                notice.form.data.notice_type.type.charAt(0).toUpperCase() +
                                notice.form.data.notice_type.type.slice(1) : 'Unknown' }}
                        </td>
                        <td class="border p-2">
                            <span v-if="notice.form.data.expiry_date">
                                {{ new Date(notice.form.data.expiry_date).toLocaleString() | ago }}
                            </span>
                            <span v-else class="text-red-500">No Expiry</span>
                        </td>
                        <td class="border p-2">
                            {{ notice.form.data.created_at ? new Date(notice.form.data.created_at).toLocaleString() :
                                'Unknown' | ago }}
                        </td>
                        <td class="flex border p-4 space-x-4">
                            <button @click="deleteNotice(notice)"
                                class="bg-red-500 text-white py-1 px-2 rounded-md hover:bg-red-600">
                                Delete
                            </button>
                            <button @click="editNotice(notice)" :disabled="!canEdit(user.id)" :class="{
                                'bg-blue-500 text-white py-1 px-2 rounded-md hover:bg-blue-600': canEdit(user.id),
                                'bg-gray-300 text-gray-500 py-1 px-2 rounded-md cursor-not-allowed': !canEdit(user.id)
                            }">
                                Edit
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4">
                <Pagination :pagination="localPagination" @paginate="fetchNotices" />
            </div>
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
                    <select v-model="form.data.notice_type_id"
                        class="text-gray-500 w-full p-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
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
                        class="text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('expiry_date') }" @input="clearError('expiry_date')">
                    <span v-if="form.hasError('expiry_date')" class="text-red-500 text-sm mt-1 block">
                        {{ form.getError('expiry_date') }}</span>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                    {{ editingNoticeId ? "Update Notice" : "Publish Notice" }}
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import CNoticesAdmin from "@/utilities/CNoticesAdmin.js";
import { ref } from 'vue';
import Pagination from "../Pagination.vue";
import axios from 'axios';
import { debounce } from "lodash";
import moment from "moment-timezone";
import LoadingBar from "../LoadingBar.vue";

export default {
    components: {
        Pagination,
        LoadingBar,
    },
    props: {
        noticesJson: {
            type: Array,
            required: true,
        },
        pagination: {
            type: Object,
            required: true,
        },
        flashSuccess: {
            type: String,
            default: ''
        },
        flashError: {
            type: String,
            default: ''
        },
        user: {
            type: Array,
            required: true
        },
    },
    data() {
        return {
            notices: ref([]),
            form: new CNoticesAdmin().form,
            errors: {},
            localPagination: { ...this.pagination },
            NoticeSearchQuery: '',
            loading: false,
            editingNoticeId: null,
            progress: 0,
            interval: null,
        };
    },
    methods: {
        canEdit(userId) {
            return userId === 1;
        },
        async addNotice() {
            try {
                if (this.editingNoticeId) {
                    await this.form.update(`/admin/notice/${this.editingNoticeId}`);
                } else {
                    await this.form.save('/admin/notice');
                }
                this.resetForm();
                window.location.reload();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Error saving notice:', error);
                }
            }
        },
        async deleteNotice(notice) {
            if (confirm("Are you sure you want to delete this notice?")) {
                try {
                    const response = await notice.delete();
                    if (response.status === 200) {
                        this.localFlashSuccess = "Notice deleted successfully!";
                        await this.fetchNotices();
                        window.location.reload();
                    }
                } catch (error) {
                    if (error.response && error.response.status === 403) {
                        this.localFlashError = "You are not authorized to delete this notice!";
                    } else {
                        this.localFlashError = "An error occurred while deleting the notice.";
                    }
                    window.location.reload();
                }
            }
        },
        async editNotice(notice) {
            this.form = new CNoticesAdmin(notice.id);
            this.form = notice.form;
            if (this.form.data.expiry_date) {
                this.form.data.expiry_date = new Date(this.form.data.expiry_date).toISOString().slice(0, 16);
            }
            this.editingNoticeId = notice.id;
        },
        resetForm() {
            this.form = new CNoticesAdmin().form;
            this.editingNoticeId = null;
        },
        async fetchNotices(url) {
            try {
                this.startLoading();
                const response = await axios.get(url, {
                    params: {
                        search: this.NoticeSearchQuery,
                    },
                });
                this.notices = response.data.notices.map(noticeJson => {
                    const notice = new CNoticesAdmin(noticeJson.id);
                    notice.form.data.name = noticeJson.name;
                    notice.form.data.description = noticeJson.description;
                    notice.form.data.notice_type = noticeJson.notice_type;
                    notice.form.data.expiry_date = noticeJson.expiry_date;
                    notice.form.data.created_at = noticeJson.created_at;
                    return notice;
                });
                this.localPagination = response.data.pagination;
                this.stopLoading();
            } catch (error) {
                console.error("Error fetching notices:", error);
                this.stopLoading();
            }
        },
        clearError(field) {
            if (this.form.hasError(field)) {
                this.$delete(this.form.errors, field);
            }
        },
        startLoading() {
            this.loading = true;
            this.progress = 0;
            this.interval = setInterval(() => {
                if (this.progress < 95) {
                    this.progress += 5;
                }
            }, 100);
        },
        stopLoading() {
            clearInterval(this.interval);
            this.progress = 100;
            setTimeout(() => {
                this.loading = false;
                this.progress = 0;
            }, 500);
        },
    },
    computed: {
        filteredNotices() {
            return this.notices;
        },
        localFlashSuccess() {
            return this.flashSuccess;
        },
        localFlashError() {
            return this.flashError;
        }
    },
    watch: {
        NoticeSearchQuery: debounce(function () {
            this.fetchNotices('/admin/notices');
        }, 300),
        flashSuccess(newVal) {
            if (newVal) {
                setTimeout(() => {
                    this.flashSuccess = "";
                }, 3000);
            }
        },
        flashError(newVal) {
            if (newVal) {
                setTimeout(() => {
                    this.flashError = "";
                }, 3000);
            }
        }
    },
    filters: {
        ago(date) {
            if (!date) return "No Expiry";
            const format = "DD/MM/YYYY, HH:mm:ss";
            const parsedDate = moment(date, format, true);
            return parsedDate.isValid() ? parsedDate.fromNow() : "Invalid date";
        },
    },
    mounted() {
        this.notices = this.noticesJson.map(noticeJson => {
            const notice = new CNoticesAdmin(noticeJson.id);
            notice.form.data.name = noticeJson.name;
            notice.form.data.description = noticeJson.description;
            notice.form.data.notice_type = noticeJson.notice_type;
            notice.form.data.expiry_date = noticeJson.expiry_date;
            notice.form.data.created_at = noticeJson.created_at;
            return notice;
        });
        this.localPagination = this.pagination;
        if (this.flashSuccess) {
            setTimeout(() => {
                this.flashSuccess = "";
            }, 3000);
        }
        if (this.flashError) {
            setTimeout(() => {
                this.flashError = "";
            }, 3000);
        }
    }
};
</script>

<style scoped>
.notification {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.25rem;
    font-size: 1rem;
    text-align: center;
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

.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 25px;
    height: 25px;
    border: 4px solid rgba(255, 255, 255, 0.2);
    border-top-color: rgba(37, 197, 239, 1);
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

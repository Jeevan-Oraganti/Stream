<template>
    <div class="lg:flex-row lg:space-x-12 items-start p-8">
        <div class="w-full mx-auto my-10 p-6 bg-white border rounded-md">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 sm:flex-auto">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="flex justify-between mb-4">
                        <span>
                            <h2 class="text-sm font-semibold mb-2 text-gray-800">Past Notices</h2>
                            <p class="text-sm text-gray-500 mb-4">All your past notices will appear here.</p>
                        </span>
                        <span>
                            <button
                            class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"><a
                            href="/admin/add-notice">Add Notice</a>
                        </button>
                    </span>
                </div>
                <div v-if="localFlashSuccess" class="notification is-success">
                    {{ localFlashSuccess }}
                    </div>
                    <div v-if="localFlashError" class="notification is-danger">
                        {{ localFlashError }}
                    </div>
                    <div class="flex items-center mb-4">
                        <div class="relative w-full">
                            <input type="text" ref="selectSearch" v-model="NoticeSearchQuery" placeholder="Search..."
                            class="text-sm text-gray-800 w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-search"></i>
                            </span>
                            <LoadingBar :progress="progress" v-if="loading" />
                        </div>
                    </div>
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr class="border-b">
                                <th scope="col"
                                    class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">#
                                </th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Title
                                </th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                    Description
                                </th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Type
                                </th>
                                <th scope="col"
                                    class="whitespace-nowrap px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                    Expiry
                                    Date
                                </th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                    Created
                                    At
                                </th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="filteredNotices.length === 0">
                                <td class="border p-5 text-center" colspan="7">
                                    <div class="flex flex-col items-center justify-center h-full">
                                        <div v-if="NoticeSearchQuery" class="flex flex-col items-center animate-pulse">
                                            <i class="fas fa-search text-4xl text-gray-400 mb-2"></i>
                                            <p class="text-lg text-gray-600 mb-2">No results found for "{{
                                                NoticeSearchQuery
                                            }}"</p>
                                            <p class="text-sm text-gray-500">Try clearing the search query.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="(notice, index) in notices" :key="notice.id" :class="{ 'bg-green-100': isActive(notice) }" class="text-gray-700">
                                <td class="border-b whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{
                                    (localPagination.current_page - 1) * localPagination.per_page + index + 1
                                }}
                                </td>
                                <td class="border-b whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                <span @click="toggleSticky(notice)" v-if="notice.form.data.is_sticky"><i class="fas fa-star" style="color:orange; cursor: pointer;"></i></span>
                                <span @click="toggleSticky(notice)" v-else><i class="far fa-star" style="cursor: pointer;"></i></span>
                                    <a :href="canEdit(user.id) ? `/admin/edit-notice/${notice.id}` : '#'" :style="{ color: canEdit(user.id) ? 'dodgerblue' : 'text-gray-900' }">
                                        {{ notice.form.data.name }}
                                    </a>
                                </td>
                                <td class="border-b px-3 py-4 text-sm text-gray-500">
                                    {{ notice.form.data.description }}
                                </td>
                                <td
                                    :class="['border-b whitespace-nowrap px-3 py-4 text-sm', notice.form.data.notice_type && notice.form.data.notice_type.color ? 'text-' + notice.form.data.notice_type.color + '-600' : 'text-gray-600']">
                                    {{
                                        notice.form.data.notice_type && notice.form.data.notice_type.type ?
                                            notice.form.data.notice_type.type.charAt(0).toUpperCase() +
                                            notice.form.data.notice_type.type.slice(1) : 'Unknown'
                                    }}
                                </td>
                                <td class="border-b whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span v-if="notice.form.data.expiry_date">
                                        {{ new Date(notice.form.data.expiry_date).toLocaleString() | ago }}
                                    </span>
                                    <span v-else class="whitespace-nowrap text-red-500">No Expiry</span>
                                </td>
                                <td class="border-b whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{
                                        notice.form.data.created_at ? new Date(notice.form.data.created_at).toLocaleString()
                                            :
                                            'Unknown' | ago
                                    }}
                                </td>
                                <td class="border-b px-3 py-4 text-sm">
                                    <span v-if="deletingNoticeId !== notice.id">
                                        <button @click="deleteNotice(notice)" class="text-red-500 hover:bg-red-900">
                                            <i class="fas fa-trash" style="color:red"></i>
                                        </button>
                                    </span>
                                    <span v-if="deletingNoticeId === notice.id">
                                        <div class="delete-loader"></div>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-4">
                        <Pagination :pagination="localPagination" @paginate="fetchNotices" />
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="inline my-10">
            <div class="w-full mx-auto my-10 p-6 bg-white border rounded-md">
                <table id="noticesTable" class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr class="border-b">
                            <th scope="col"
                                class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">#
                            </th>
                            <th scope="col"
                                class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Title
                            </th>
                            <th scope="col"
                                class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                Description
                            </th>
                            <th scope="col"
                                class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Type
                            </th>
                            <th scope="col"
                                class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Expiry
                                Date
                            </th>
                            <th scope="col"
                                class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Created
                                At
                            </th>
                            <th scope="col"
                                class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in tableData" :key="index">
                            <td class="border-b px-3 py-4 text-sm text-gray-500">{{ row.no }}</td>
                            <td class="font-semibold border-b px-3 py-4 text-sm text-gray-900">
                                <span v-if="row.is_sticky"><i class="fas fa-star" style="color:orange;"></i></span>
                                <span v-else><i class="far fa-star"></i></span>
                                {{ row.name }}
                            </td>
                            <td class="border-b px-3 py-4 text-sm text-gray-500">{{ row.description }}</td>
                            <td class="border-b px-3 py-4 text-sm text-gray-500"
                                :class="row.notice_type && row.notice_type.color ? 'text-' + row.notice_type.color + '-500' : 'text-gray-500'">
                                {{ row.notice_type }}
                            </td>
                            <td class="border-b px-3 py-4 text-sm text-gray-500">{{ row.expiry_date }}</td>
                            <td class="border-b px-3 py-4 text-sm text-gray-500">{{ row.created_at | ago }}</td>
                            <td class="border-b px-3 py-4 text-sm text-gray-500 justify-center align-center">
                                <button @click="deleteNotice(row.id)" class="text-red-500 hover:bg-red-900">
                                    <i class="fas fa-trash mr-3" style="color:red"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> -->
    </div>
</template>

<script>
import CNotice from "@/components/notice/CNotice.js";
import { ref } from 'vue';
import Pagination from "@/components/Pagination.vue";
import axios from 'axios';
import { debounce } from "lodash";
import moment from "moment-timezone";
import LoadingBar from "@/components/LoadingBar.vue";
import DataTable from "datatables.net-dt";
import "datatables.net-dt/css/dataTables.dataTables.css";
import "datatables.net-dt";

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
            type: Object,
            required: true
        },
    },
    data() {
        return {
            notices: ref([]),
            form: new CNotice(),
            errors: {},
            localPagination: { ...this.pagination },
            NoticeSearchQuery: '',
            loading: false,
            editingNoticeId: null,
            progress: 0,
            interval: null,
            deletingNoticeId: null,
            baseUrl: "/admin/notices",
        };
    },
    methods: {
        isActive(notice) {
            const now = new Date();
            const expiryDate = new Date(notice.form.data.expiry_date);
            return expiryDate > now;
        },
        async toggleSticky(notice) {
            try {
                const response = await notice.toggleSticky();
                if (response.status === 200) {
                    this.localFlashSuccess = "Notice updated successfully!";
                    await this.fetchNotices();
                }
            } catch (error) {
                console.error("Error toggling sticky notice:", error);
                this.localFlashError = "An error occurred while updating the notice.";
            } finally {
                window.location.href = '/admin/notices';
            }
        },
        focusSearchBar(event) {
            if (event.key === "/") {
                event.preventDefault();
                this.$refs.selectSearch.focus();
            }
        },
        canEdit(userId) {
            return userId === 1;
        },
        canDelete(userId) {
            return userId === 1;
        },
        async deleteNotice(notice) {
            if (confirm("Are you sure you want to delete this notice?")) {
            this.deletingNoticeId = notice.id;
            try {
                const response = await notice.delete();
                if (response.status === 200) {
                this.localFlashSuccess = "Notice deleted successfully!";
                await this.fetchNotices();
                }
            } catch (error) {
                if (error.response && error.response.status === 403) {
                this.localFlashError = "You are not authorized to delete this notice!";
                } else {
                this.localFlashError = "An error occurred while deleting the notice.";
                }
            } finally {
                window.location.href = '/admin/notices';
                this.deletingNoticeId = null;
            }
            }
        },
        async fetchNotices(url = this.baseUrl) {
            try {
                if (this.loading) return;
                this.startLoading();
                const results = await CNotice.fetchNoticesListForAdmin(url, this.NoticeSearchQuery);
                this.notices = results.notices;
                this.localPagination = results.pagination;
                this.stopLoading();
            } catch (error) {
                console.error("Error fetching notices:", error);
                this.stopLoading();
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
        },
        tableData() {
            return this.notices.map((notice, index) => ({
                no: (this.localPagination.current_page - 1) * this.localPagination.per_page + index + 1,
                id: notice.form.data.id,
                name: notice.form.data.name,
                description: notice.form.data.description,
                is_sticky: notice.form.data.is_sticky,
                notice_type: notice.form.data.notice_type ? notice.form.data.notice_type.type : 'Unknown',
                expiry_date: notice.form.data.expiry_date ? new Date(notice.form.data.expiry_date).toLocaleString() : 'No Expiry',
                created_at: notice.form.data.created_at ? new Date(notice.form.data.created_at).toLocaleString() : 'Unknown',

            }));
        }
    },
    watch: {
        NoticeSearchQuery: debounce(function () {
            this.fetchNotices();
        }, 500),
        flashSuccess(newVal) {
            if (newVal) {
                setTimeout(() => {
                    this.localFlashSuccess = "";
                }, 3000);
            }
        },
        flashError(newVal) {
            if (newVal) {
                setTimeout(() => {
                    this.localFlashError = "";
                }, 3000);
            }
        },
    },
    filters: {
        ago(date) {
            if (!date) return "No Expiry";
            const format = "DD/MM/YYYY, HH:mm:ss";
            const parsedDate = moment(date, format, true);
            return parsedDate.isValid() ? parsedDate.fromNow() : "Invalid date";
        },
    },
    async mounted() {
        this.deletingNoticeId = null;

        await this.fetchNotices();

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

        var table = new DataTable('#noticesTable');

        window.addEventListener("keydown", this.focusSearchBar);
    },
    beforeUnmount() {
        window.removeEventListener("keydown", this.focusSearchBar);
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

.delete-loader {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-top-color: rgba(255, 0, 0, 1);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    vertical-align: middle;
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

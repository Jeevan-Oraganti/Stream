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
                            <button @click="addNotice"
                                class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Add Notice
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
                    <table id="noticesTable" class="display min-w-full divide-y divide-gray-300">
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
                                    Scheduled
                                    At
                                </th>
                                <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                    Active
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
                                <td class="border p-5 text-center" colspan="9">
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
                            <tr v-for="(notice, index) in notices" :key="notice.id"
                                :class="{ 'bg-green-100': isActive(notice) }" class="text-gray-700">
                                <td class="border-b whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{
                                    (localPagination.current_page - 1) * localPagination.per_page + index + 1
                                }}
                                </td>
                                <td
                                    class="border-b whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                    <span @click="toggleSticky(notice)" v-if="notice.form.data.is_sticky"><i
                                            class="fas fa-star" style="color:orange; cursor: pointer;"></i></span>
                                    <span @click="toggleSticky(notice)" v-else><i class="far fa-star"
                                            style="cursor: pointer;"></i></span>
                                    <span @click="editNotice(notice)" class="cursor-pointer"
                                        :style="{ color: canEdit(user.id) ? 'dodgerblue' : 'text-gray-900' }">
                                        {{ notice.form.data.name }}
                                    </span>
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
                                        {{ new Date(notice.form.data.expiry_date) | ago }}
                                    </span>
                                    <span v-else class="whitespace-nowrap text-red-500">No Expiry</span>
                                </td>
                                <td class="border-b whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{
                                        notice.form.data.scheduled_at ? new
                                            Date(notice.form.data.scheduled_at)
                                            :
                                            'Unknown' | ago
                                    }}
                                </td>
                                <td class="border-b whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{
                                        isActive(notice) ? 'Yes' : 'No'
                                    }}
                                </td>
                                <td class="border-b whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{
                                        notice.form.data.created_at ? new Date(notice.form.data.created_at)
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
        <notice-form :notice="notice"></notice-form>
    </div>
</template>

<script>
import CNotice from "@/components/notice/CNotice.js";
import CNotices from "@/components/notice/CNotices.js";
import { ref } from 'vue';
import Pagination from "@/components/Pagination.vue";
import { debounce } from "lodash";
import moment from "moment-timezone";
import LoadingBar from "@/components/LoadingBar.vue";
import NoticeForm from "@/components/notice/admin/NoticeForm.vue";
import $ from 'jquery';
import 'datatables.net-dt';
import DataTables from "datatables.net";

export default {
    components: {
        Pagination,
        LoadingBar,
        NoticeForm,
    },
    props: {
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
            localFlashSuccess: '',
            localFlashError: '',
            notice: null
        };
    },
    methods: {
        addNotice() {
            this.$modal.show('add-edit-notice');
            this.notice = {};
        },
        editNotice(notice) {
            this.$modal.show('add-edit-notice');
            this.notice = notice.form.data;
        },
        isActive(notice) {
            const now = new Date();
            const expiryDate = new Date(notice.form.data.expiry_date);
            return !!(expiryDate > now && notice.form.data.is_active);

        },
        async toggleSticky(notice) {
            try {
                const response = await notice.toggleSticky();
                if (response.status === 200) {
                    notice.form.data.is_sticky = response.data.notice.is_sticky;
                    this.localFlashSuccess = "Notice updated successfully!";
                    setTimeout(() => {
                        this.localFlashSuccess = "";
                    }, 3000);
                }
                if (notice.form.data.is_sticky) {
                    this.notices.forEach(n => {
                        if (n.id !== notice.id) {
                            n.form.data.is_sticky = false;
                        }
                    });
                }
            } catch (error) {
                console.error("Error toggling sticky notice:", error);
                this.localFlashError = "An error occurred while updating the notice.";
                setTimeout(() => {
                    this.localFlashSuccess = "";
                }, 3000);
            }
        },
        focusSearchBar(event) {
            if (event.ctrlKey && event.key === "k") {
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
            this.deletingNoticeId = notice.id;
            if (confirm("Are you sure you want to delete this notice?")) {
                try {
                    const response = await notice.delete();
                    console.log('Response', response);
                    if (response.status === 200) {
                        this.localFlashSuccess = "Notice deleted successfully!";
                        this.notices = this.notices.filter(n => n.id !== notice.id);
                        setTimeout(() => {
                            this.localFlashSuccess = "";
                        }, 3000);
                    }
                } catch (error) {
                    if (error.response && error.response.status === 403) {
                        this.localFlashError = "You are not authorized to delete this notice!";
                        setTimeout(() => {
                            this.localFlashSuccess = "";
                        }, 3000);
                    } else {
                        this.localFlashError = "An error occurred while deleting the notice.";
                        setTimeout(() => {
                            this.localFlashSuccess = "";
                        }, 3000);
                    }
                } finally {
                    this.deletingNoticeId = null;
                }
            }
        },
        async fetchNotices(url = this.baseUrl) {
            try {
                if (this.loading) return;
                this.startLoading();
                const results = await CNotices.fetchNoticesListForAdmin(url, this.NoticeSearchQuery);
                this.notices = results.notices;
                this.localPagination = results.pagination;
                this.initializeDataTable();
                this.stopLoading();
            } catch (error) {
                console.error("Error fetching notices:", error);
                this.stopLoading();
            }
        },
        initializeDataTable() {
            this.$nextTick(() => {
                $('#noticesTable').DataTable({
                    searching: false,
                });
            });
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
    },
    watch: {
        NoticeSearchQuery: debounce(function () {
            this.fetchNotices();
        }, 500),
    },
    filters: {
        ago(date) {
            if (!date) return "No Expiry";
            const format = "DD/MM/YYYY, HH:mm:ss";
            const parsedDate = moment(date, format, true);
            return parsedDate.isValid() ? parsedDate.fromNow() : "No Date";
        },
    },
    async mounted() {
        this.deletingNoticeId = null;

        await this.fetchNotices();

        if (this.flashSuccess) {
            this.localFlashSuccess = this.flashSuccess;
            setTimeout(() => {
                this.localFlashSuccess = "";
            }, 3000);
        }
        if (this.flashError) {
            this.localFlashError = this.flashError;
            setTimeout(() => {
                this.localFlashError = "";
            }, 3000);
        }

        window.addEventListener("keydown", this.focusSearchBar);
    },
    beforeUnmount() {
        window.removeEventListener("keydown", this.focusSearchBar);
    },
};
</script>

<style scoped>
.notification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    width: auto;
    padding: 1rem;
    border-radius: 0.25rem;
    font-size: 1rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

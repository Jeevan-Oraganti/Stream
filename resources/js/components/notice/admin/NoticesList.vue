<!-- filepath: /home/bnetworks/websites/stream/resources/js/components/notice/admin/NoticesList.vue -->
<template>
    <div>
        <notice-header></notice-header>
        <div class="lg:flex-row lg:space-x-12 items-start p-8">
            <div class="w-full mx-auto p-6 bg-white border rounded-md">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 sm:flex-auto">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="flex justify-between mb-4">
                            <span class="flex flex-col">
                                <span class="text-sm font-semibold mb-2 text-gray-800">Notices</span>
                                <span class="text-sm text-gray-500 mb-4">All your notices will appear here.</span>
                            </span>
                            <span class="flex flex-col">
                                <button @click="addNotice"
                                    class="block justify-center rounded-md bg-indigo-600 px-6 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Add Notice
                                </button>
                                <button @click="changeNoticeTypeColor"
                                    class="block justify-center rounded-md bg-green-500 px-4 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-green-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                                    Change Notice Color
                                </button>
                            </span>
                        </div>
                        <div v-if="localFlashSuccess" class="notification is-success">
                            {{ localFlashSuccess }}
                        </div>
                        <div v-if="localFlashError" class="notification is-danger">
                            {{ localFlashError }}
                        </div>
                        <alert-about-notices :notices="notices"></alert-about-notices>
                        <div class="flex items-center mb-4">
                            <div class="relative w-full">
                                <input type="text" ref="selectSearch" v-model="NoticeSearchQuery"
                                    placeholder="Search..."
                                    class="text-sm text-gray-800 w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-search"></i>
                                </span>
                                <LoadingBar :progress="progress" v-if="loading" />
                            </div>
                        </div>
                        <div class="is-overflow-auto">
                            <table id="noticesTable" class="display min-w-full divide-y divide-gray-300">
                                <thead>
                                    <tr>
                                        <th scope="col"
                                            class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                            #
                                        </th>
                                        <th scope="col" class="whitespace-nowrap px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Title
                                        </th>
                                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Description
                                        </th>
                                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Type
                                        </th>
                                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Scheduled
                                            At
                                        </th>
                                        <th scope="col"
                                            class="whitespace-nowrap px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Expiry
                                            Date
                                        </th>
                                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Active
                                        </th>
                                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Created
                                            At
                                        </th>
                                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Delete
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table rows are generated by DataTable dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <notice-form :notice="notice" :stickyNoticeId="stickyNoticeId"></notice-form>
        <color-types @color-updated="refreshNotices"></color-types>
    </div>
</template>
<script>
import CNotice from "@/components/notice/CNotice.js";
import CNotices from "@/components/notice/CNotices.js";
import { debounce } from "lodash";
import moment from "moment-timezone";
import LoadingBar from "@/components/LoadingBar.vue";
import NoticeForm from "./NoticeForm.vue";
import $ from 'jquery';
import ColorTypes from "./ColorTypes.vue";
import DataTable from 'datatables.net';
import DataTablesCore from 'datatables.net';
import AlertAboutNotices from "@/components/notice/admin/AlertAboutNotices.vue";

DataTable.use(DataTablesCore);
export default {
    components: {
        AlertAboutNotices,
        LoadingBar,
        NoticeForm,
        ColorTypes
    },
    props: {
        user: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            notices: [],
            errors: {},
            NoticeSearchQuery: '',
            loading: false,
            editingNoticeId: null,
            progress: 0,
            interval: null,
            deletingNoticeId: null,
            baseUrl: "/admin/notices/all",
            localFlashSuccess: '',
            localFlashError: '',
            notice: new CNotice(),
            stickyNoticeId: null,
            window: window.CHARTINK
        };
    },
    methods: {
        async refreshNotices() {
            await this.fetchNotices();
            this.initializeDataTable();
        },
        addNotice() {
            this.notice = new CNotice()
            this.$modal.show('add-edit-notice');
        },
        convertDateTime(dateTime) {
            // Convert format to YYYY-MM-DDTHH:MM for datetime-local input
            const utcDate = new Date(dateTime);
            // Convert to IST timezone
            const istDate = new Intl.DateTimeFormat("en-CA", {
                timeZone: "Asia/Kolkata",
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "2-digit",
                minute: "2-digit",
                hour12: false
            }).format(utcDate);
            return istDate.replace(",", "").replace(/\//g, "-").replace(" ", "T")
        },
        editNotice(noticeId) {
            this.notice = this.notices.find((notice) => notice.id == noticeId);
            if (this.notice.form.expiry_date) {
                this.notice.form.expiry_date = this.convertDateTime(this.notice.form.expiry_date);
            }
            if (this.notice.form.scheduled_at) {
                this.notice.form.scheduled_at = this.convertDateTime(this.notice.form.scheduled_at);
            }
            this.$modal.show('add-edit-notice');
        },
        isActive(notice) {
            const now = new Date();
            const expiryDate = new Date(notice.form.expiry_date);
            return !!(expiryDate > now && notice.form.is_active);
        },
        async toggleSticky(id) {
            try {
                let notice = this.notices.find((notice) => notice.id == id);
                this.notices.forEach((notice) => {
                    if (notice.form.is_sticky && notice.id != id) {
                        notice.form.is_sticky = false
                    }
                });
                notice.form.is_sticky = !notice.form.is_sticky
                this.initializeDataTable()
                const response = await notice.toggleSticky();
                if (response.status === 200) {
                    notice.form.is_sticky = response.data.notice.is_sticky;
                    this.localFlashSuccess = "Notice updated successfully!";
                }
            } catch (error) {
                console.error("Error toggling sticky notice:", error);
                this.localFlashError = "An error occurred while updating the notice.";
            }
        },
        focusSearchBar(event) {
            if (event.ctrlKey && event.key === "k") {
                event.preventDefault();
                this.$refs.selectSearch.focus();
            }
        },
        canEdit() {
            return this.user.id == 1;
        },
        canDelete() {
            return this.user.id == 1;
        },
        async deleteNotice(id) {
            const notice = new CNotice(id);
            this.deletingNoticeId = notice.id;
            if (confirm("Are you sure you want to delete this notice?")) {
                try {
                    const response = await notice.delete();
                    console.log('Response', response);
                    if (response.status === 200) {
                        this.localFlashSuccess = "Notice deleted successfully!";
                        this.notices = this.notices.filter(n => n.id !== notice.id);
                    }
                } catch (error) {
                    if (error.response && error.response.status === 403) {
                        this.localFlashError = "You are not authorized to delete this notice!";
                    } else {
                        this.localFlashError = "An error occurred while deleting the notice.";
                    }
                } finally {
                    this.deletingNoticeId = null;
                    await this.fetchNotices();
                }
            }
        },
        async fetchNotices(url = this.baseUrl) {
            try {
                if (this.loading) return;
                this.startLoading();
                const response = await CNotices.fetchNoticesListForAdmin(url, this.NoticeSearchQuery);
                if (response && response.data && Array.isArray(response.data.notices)) {
                    this.notices = response.data.notices.map(notice => new CNotice(notice.id, notice));
                    const stickyNotice = this.notices.find(notice => notice.form.is_sticky);
                    this.stickyNoticeId = stickyNotice ? stickyNotice.form.id : null;
                } else {
                    console.error("Invalid API Response Structure:", response.data);
                    this.notices = [];
                }
                this.initializeDataTable();
                this.stopLoading();
            } catch (error) {
                console.error("Error fetching notices:", error);
                this.stopLoading();
            }
        },
        initializeDataTable() {
            this.$nextTick(() => {
                if ($.fn.DataTable.isDataTable("#noticesTable")) {
                    $("#noticesTable").DataTable().clear().destroy();
                }
                $("#noticesTable").DataTable({
                    order: [[7]], // Sort by created_at column
                    responsive: true,
                    processing: true,
                    paging: true,
                    searching: false,
                    destroy: true,
                    data: this.notices,
                    columns: [
                        {
                            data: "id",
                            title: "ID",
                            orderable: true
                        },
                        {
                            data: "name",
                            title: "Title",
                            render: function (data, type, row, meta) {
                                if (!row || !row.id) {
                                    return "Invalid Data";
                                }
                                const starIcon = row.form.is_sticky
                                    ? '<i class="fas fa-star text-yellow-500"></i>'
                                    : '<i class="far fa-star"></i>';
                                return `<span class="whitespace-nowrap cursor-pointer toggle-sticky" data-id="${row.id}">${starIcon}</span>
                                        <span class="whitespace-nowrap text-blue-500 whitespace-nowrap cursor-pointer edit-notice" data-notice='${JSON.stringify(row)}'>${row.form.name || "N/A"}</span>`;
                            }
                        },
                        {
                            data: "description",
                            title: "Description",
                            render: (data, type, row, meta) => {
                                return `<span class="text-black"> ${row.form.description || "No description"}`
                            }
                        },
                        {
                            data: "notice_type", title: "Type", render: (data, type, row, meta) => {
                                if (row.form && row.form.notice_type) {
                                    const color = row.form.notice_type.color ? row.form.notice_type.color : "#808080";
                                    return `<span class="capitalize" style="color: ${color};">${row.form.notice_type.type}</span>`;
                                }
                                return "No Type";
                            }
                        },
                        {
                            data: "scheduled_at",
                            title: "Scheduled At",
                            render: (data, type, row, meta) => {
                                let result = '';
                                if (row.form.scheduled_at === null) {
                                    result += `<span class="whitespace-nowrap text-red-500">No Schedule</span>`;
                                } else {
                                    const scheduledAt = moment(row.form.scheduled_at);
                                    const timeLeft = scheduledAt.diff(moment(), 'milliseconds');
                                    if (timeLeft >= 0) {
                                        result += `<span class="whitespace-nowrap text-blue-500">Scheduled for ${scheduledAt.format("MMM D, YYYY [at] h:mm A")}</span>`;
                                    } else {
                                        result += `<span class="whitespace-nowrap text-green-500">Published on ${scheduledAt.format("MMM D, YYYY [at] h:mm A")}</span>`;
                                    }
                                }

                                if (row.form.recurrence) {
                                    result += `<br><span class="text-gray-500">Recurrence: ${row.form.recurrence}</span>`;
                                }

                                if (row.form.recurrence_days && Array.isArray(row.form.recurrence_days) && row.form.recurrence_days.length > 0) {
                                    result += `<br><span class="text-gray-500">Days: ${row.form.recurrence_days.join(', ')}</span>`;
                                }

                                return result;
                            }
                        },
                        {
                            data: "expiry_date",
                            title: "Expiry Date",
                            render: (data, type, row, meta) => {
                                if (row.form.expiry_date === null) {
                                    return `<span class="text-blue-500">No Expiry</span>`;
                                }
                                const expiryDate = moment(row.form.expiry_date);
                                const daysLeft = expiryDate.diff(moment(), 'milliseconds');
                                if (daysLeft <= 0) {
                                    return `<span class="text-red-500">Expired</span>`;
                                }
                                return `<span class="text-green-500">${expiryDate.format("MMMM D, YYYY [at] h:mm A")}</span>`;
                            }
                        },
                        {
                            data: "is_active",
                            title: "Active",
                            orderable: true,
                            render: (data, type, row, meta) => row.form.is_active ? `<span class="text-green-500">Yes</span>` : `<span class="text-red-500">No</span>`
                        },
                        {
                            data: "created_at",
                            title: "Created At",
                            render: (data, type, row, meta) => {
                                return `<span class="text-black"> ${row.form.created_at ? moment(row.form.created_at).format("MMM D, YYYY [at] h:mm A") : "No Date"}`
                            },
                            orderable: true,
                        },
                        {
                            data: "id",
                            title: "Delete",
                            orderable: false,
                            render: (data, type, row, meta) => `<button class="delete-notice ${this.canDelete() ? 'text-red-500' : 'text-gray-500'}" data-id="${data}"><i class="fas fa-trash"></i></button>`
                        }
                    ],
                    drawCallback: () => {
                        document.querySelectorAll(".toggle-sticky").forEach(el => {
                            el.addEventListener("click", () => {
                                this.toggleSticky(el.dataset.id);
                            });
                        });
                        document.querySelectorAll(".edit-notice").forEach(el => {
                            el.addEventListener("click", () => {
                                this.editNotice(JSON.parse(el.dataset.notice).id);
                            });
                        });
                        document.querySelectorAll(".delete-notice").forEach(el => {
                            el.addEventListener("click", () => {
                                this.deleteNotice(el.dataset.id);
                            });
                        });
                    },
                    rowCallback: function (row, data) {
                        if (data.form.is_active) {
                            $(row).css('background-color', '#d4edda');
                        }
                    },
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
        changeNoticeTypeColor() {
            this.$modal.show('change-notice-type-color');
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
        'localFlashSuccess': {
            handler(newVal, oldVal) {
                if (newVal.length > 0) {
                    setTimeout(() => {
                        this.localFlashSuccess = ''
                    }, 3000)
                }
            }
        },
        'localFlashError': {
            handler(newVal, oldVal) {
                if (newVal.length > 0) {
                    setTimeout(() => {
                        this.localFlashError = ''
                    }, 3000)
                }
            }
        },
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
        this.$nextTick(() => {
            if (!$.fn.DataTable.isDataTable("#noticesTable")) {
                this.dataTable = $("#noticesTable").DataTable();
            }
        });
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
    }
};
</script>
<style scoped>
.search-popup {
    position: absolute;
    background: white;
    border: 1px solid #ccc;
    padding: 5px 10px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    display: none;
    cursor: pointer;
    z-index: 1000;
    font-size: 12px;
}
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

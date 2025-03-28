<!-- filepath: /home/bnetworks/websites/stream/resources/js/components/notice/admin/NoticesList.vue -->
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
                        <span class="flex-col">
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
                    <div class="is-overflow-auto">
                        <table id="noticesTable" class="display min-w-full divide-y divide-gray-300">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="py-4 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">ID
                                    </th>
                                    <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                        Title
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
        <notice-form :notice="notice" :sticky-notice-id="stickyNoticeId"></notice-form>
        <color-types @color-updated="refreshNotices"></color-types>
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
import DataTable from 'datatables.net-dt';
import DataTablesCore from 'datatables.net';

DataTable.use(DataTablesCore);

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
            notices: [],
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
            notice: null,
            stickyNoticeId: null,
        };
    },
    methods: {
        // Refreshes the notices list and reinitializes the DataTable
        async refreshNotices() {
            await this.fetchNotices();
            this.initializeDataTable();
        },
        // Opens the modal to change the notice type color
        changeNoticeTypeColor() {
            this.$modal.show('change-notice-type-color');
        },
        // Opens the modal to add a new notice and initializes the DataTable
        addNotice() {
            this.$modal.show('add-edit-notice');
            this.notice = {};
            this.initializeDataTable();
        },
        // Opens the modal to edit an existing notice and fetches the notices
        editNotice(notice) {
            this.form = new CNotice(notice);
            this.$modal.show('add-edit-notice');
            this.notice = typeof notice === 'string' ? JSON.parse(notice) : notice;
            this.fetchNotices();
        },
        // Checks if a notice is active based on its expiry date and active status
        isActive(notice) {
            const now = new Date();
            const expiryDate = new Date(notice.form.data.expiry_date);
            return !!(expiryDate > now && notice.form.data.is_active);
        },
        // Toggles the sticky status of a notice and updates the notices list
        async toggleSticky(id) {
            const notice = new CNotice(id);
            try {
                const response = await notice.toggleSticky();
                if (response.status === 200) {
                    notice.form.data.is_sticky = response.data.notice.is_sticky;
                    this.localFlashSuccess = "Notice updated successfully!";
                }
                const index = this.notices.findIndex(n => n.id === id);
                if (index !== -1) {
                    this.$set(this.notices, index, { ...this.notices[index], is_sticky: notice.form.data.is_sticky });
                }
                this.notices.forEach((n, i) => {
                    if (i !== index) {
                        this.$set(this.notices, i, { ...n, is_sticky: false });
                    }
                });

                await this.fetchNotices();

                setTimeout(() => {
                    this.localFlashSuccess = "";
                }, 3000);
            } catch (error) {
                console.error("Error toggling sticky notice:", error);
                this.localFlashError = "An error occurred while updating the notice.";
                setTimeout(() => {
                    this.localFlashSuccess = "";
                }, 3000);
            }
        },
        // Focuses the search bar when Ctrl+K is pressed
        focusSearchBar(event) {
            if (event.ctrlKey && event.key === "k") {
                event.preventDefault();
                this.$refs.selectSearch.focus();
            }
        },
        // Checks if the user has permission to edit notices
        canEdit() {
            return this.user.id === 1;
        },
        // Checks if the user has permission to delete notices
        canDelete() {
            return this.user.id === 1;
        },
        // Deletes a notice after confirming and updates the notices list
        async deleteNotice(id) {
            if (!this.canDelete()) {
                this.localFlashError = "You do not have permission to delete this notice";
                return;
            }
            const notice = new CNotice(id);

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
                    await this.fetchNotices();
                }
            }
        },
        // Fetches the list of notices from the server
        async fetchNotices(url = this.baseUrl) {
            try {
                if (this.loading) return;
                this.startLoading();
                const response = await CNotices.fetchNoticesListForAdmin(url, this.NoticeSearchQuery);
                if (response && response.data && Array.isArray(response.data.notices)) {
                    this.notices = response.data.notices.map(notice => new CNotice(notice.id, notice));
                    const stickyNotice = this.notices.find(notice => notice.form.data.is_sticky);
                    this.stickyNoticeId = stickyNotice ? stickyNotice.form.data.id : null;
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
        // Initializes the DataTable with the notices data
        initializeDataTable() {
            this.$nextTick(() => {
                if ($.fn.DataTable.isDataTable("#noticesTable")) {
                    $("#noticesTable").DataTable().clear().destroy();
                }
                let user;
                $("#noticesTable").DataTable({
                    order: [[7]], // Sort by created_at column
                    responsive: true,
                    processing: true,
                    paging: true,
                    searching: false,
                    destroy: true,
                    data: this.notices.map(notice => ({
                        id: notice.form.data.id,
                        is_sticky: notice.form.data.is_sticky,
                        name: notice.form.data.name,
                        description: notice.form.data.description,
                        notice_type_id: notice.form.data.notice_type_id,
                        expiry_date: notice.form.data.expiry_date,
                        scheduled_at: notice.form.data.scheduled_at,
                        recurrence: notice.form.data.recurrence,
                        recurrence_days: notice.form.data.recurrence_days,
                        created_at: notice.form.data.created_at,
                        is_active: notice.form.data.is_active,
                        notice_type: notice.form.data.notice_type,
                    })),
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
                                    console.error("Row ID is undefined!");
                                    return "Invalid Data";
                                }

                                const starIcon = row.is_sticky
                                    ? '<i class="fas fa-star text-yellow-500"></i>'
                                    : '<i class="far fa-star"></i>';

                                return `<span class="cursor-pointer toggle-sticky" data-id="${row.id}">${starIcon}</span>
                                        <span class="text-blue-500 whitespace-nowrap cursor-pointer edit-notice" data-notice='${JSON.stringify(row)}'>${data || "N/A"}</span>`;
                            }
                        },
                        { data: "description", title: "Description", render: (data) => `<span style="color: #2d3748">${data}</span>` || "No description" },
                        {
                            data: "notice_type", title: "Type", render: (data) => {
                                if (data && data.type) {
                                    const type = data.type.charAt(0).toUpperCase() + data.type.slice(1);
                                    const color = data.color ? data.color : "#808080";
                                    return `<span style="color: ${color};">${type}</span>`;
                                }
                                return "No Type";
                            }
                        },
                        {
                            data: "expiry_date",
                            title: "Expiry Date",
                            render: (data) => {
                                if (data === null) {
                                    return `<span class="text-blue-500">No Expiry</span>`;
                                }
                                const expiryDate = moment(data);
                                const daysLeft = expiryDate.diff(moment(), 'days');
                                if (daysLeft < 0) {
                                    return `<span class="text-red-500">Expired</span>`;
                                }
                                return `<span class="text-green-500">${expiryDate.format("MMMM D, YYYY [at] h:mm A")}</span>`;
                            }
                        },
                        {
                            data: "scheduled_at",
                            title: "Scheduled At",
                            render: (data, type, row) => {
                                if (data === null && !row.recurrence) {
                                    return `<span class="text-red-500">No Schedule</span>`;
                                }
                                const scheduledAt = moment(data);
                                const timeLeft = scheduledAt.diff(moment(), 'milliseconds');
                                const recurrence = row.recurrence ? ` (${row.recurrence.charAt(0).toUpperCase() + row.recurrence.slice(1)})` : "";
                                const recurrenceDays = row.recurrence_days ? ` ${row.recurrence_days.join(", ")}` : "";
                                if (timeLeft >= 0) {
                                    return `<span class="text-blue-500">Scheduled for ${scheduledAt.format("MMM D, YYYY [at] h:mm A")}${recurrence}${recurrenceDays}</span>`;
                                } else {
                                    return `<span class="text-green-500">Published on ${scheduledAt.format("MMM D, YYYY [at] h:mm A")}${recurrence}${recurrenceDays}</span>`;
                                }
                            }
                        },
                        {
                            data: "is_active",
                            title: "Active",
                            orderable: true,
                            render: (data) => data ? `<span class="text-green-500">Yes</span>` : `<span class="text-red-500">No</span>`
                        },
                        {
                            data: "created_at",
                            title: "Created At",
                            render: (data) => data ? moment(data).format("MMM D, YYYY [at] h:mm A") : "No Date",
                            orderable: true,
                        },
                        {
                            data: "id",
                            title: "Delete",
                            orderable: false,
                            render: (data) => `<button class="delete-notice ${this.canDelete() ? 'text-red-500' : 'text-gray-500'}" data-id="${data}"><i class="fas fa-trash"></i></button>`
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
                                this.editNotice(el.dataset.notice);
                            });
                        });

                        document.querySelectorAll(".delete-notice").forEach(el => {
                            el.addEventListener("click", () => {
                                this.deleteNotice(el.dataset.id);
                            });
                        });
                    },
                    rowCallback: function (row, data) {
                        if (data.is_active) {
                            $(row).css('background-color', '#d4edda');
                        }
                    },
                });
            });
        },
        // Starts the loading animation and progress bar
        startLoading() {
            this.loading = true;
            this.progress = 0;
            this.interval = setInterval(() => {
                if (this.progress < 95) {
                    this.progress += 5;
                }
            }, 100);
        },
        // Stops the loading animation and progress bar
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
        // Returns the filtered list of notices
        filteredNotices() {
            return this.notices;
        },
    },
    watch: {
        // Watches for changes in the search query and fetches notices
        NoticeSearchQuery: debounce(function () {
            this.fetchNotices();
        }, 500),
        // Clears the success message after a timeout
        'localFlashSuccess': {
            handler(newVal, oldVal) {
                if (newVal.length > 0) {
                    setTimeout(() => {
                        this.localFlashSuccess = ''
                    }, 3000)
                }
            }
        },
        // Clears the error message after a timeout
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
        // Formats a date to show how long ago it was
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

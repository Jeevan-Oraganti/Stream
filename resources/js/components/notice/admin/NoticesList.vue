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
                        <alert-about-notices :notices="notices.noticesArray"></alert-about-notices>
                        <div class="flex items-center mb-4">
                            <div class="relative w-full">
                                <input type="text" ref="selectSearch" v-model="NoticeSearchQuery"
                                    placeholder="Search..."
                                    class="text-sm text-gray-800 w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <i class="fas fa-search"></i>
                                </span>
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
                                        <th scope="col"
                                            class="whitespace-nowrap px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Title
                                        </th>
                                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Description
                                        </th>
                                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Type
                                        </th>
                                        <th scope="col" class="px-3 py-4 text-left text-sm font-semibold text-gray-900">
                                            Event Date
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
                                            Views
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
        <notice-form :notice="notice"></notice-form>
        <color-types @color-updated="refreshNotices"></color-types>
    </div>
</template>
<script>
import CNotice from "@/components/notice/CNotice.js";
import CNotices from "@/components/notice/CNotices.js";
import { debounce } from "lodash";
import moment from "moment-timezone";
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
            NoticeSearchQuery: '',
            baseUrl: "/admin/notices/all",
            localFlashSuccess: '',
            localFlashError: '',
            notice: new CNotice(),
            window: window.CHARTINK,
            notices: new CNotices(this)
        };
    },
    methods: {
        // Updates the notice colors by re-fetching notices and reinitializing the DataTable
        async colorUpdate() {
            await this.fetchNotices();
            this.initializeDataTable();
        },
        async refreshNotices() {
            await this.fetchNotices();
            this.initializeDataTable();
        },
        // Creates a new notice instance and opens the modal for adding/editing a notice
        addNotice() {
            this.notice = new CNotice();
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
        // Finds and sets the selected notice for editing, updates its datetime, and opens the modal
        editNotice(noticeId) {
            this.notice = this.notices.noticesArray.find((notice) => notice.id == noticeId);
            this.notice.updateDateTime();
            this.$modal.show('add-edit-notice');
        },
        isActive(notice) {
            const now = new Date();
            const expiryDate = new Date(notice.form.expiry_date);
            return !!(expiryDate > now && notice.form.is_active);
        },
        // Toggles the `is_sticky` property of a specific notice
        async toggleSticky(id) {
            let notice = this.notices.noticesArray.find((notice) => notice.id == id);
            notice.toggleSticky(this);
        },
        // Focuses on the search bar when "Ctrl + K" is pressed
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
        // Deletes a specific notice by finding it in the array and calling its delete method
        async deleteNotice(id) {
            let notice = this.notices.noticesArray.find((notice) => notice.id == id);
            notice.delete(this);
        },
        // Fetches notices from the server, optionally using a provided URL
        fetchNotices(url = this.baseUrl) {
            this.notices.fetchNotices(url);
        },
        // Initializes the DataTable with the fetched notices
        initializeDataTable() {
            this.notices.initializeDataTable();
        },
        // Opens the modal to change the notice type color
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
        this.$nextTick(() => {
            if (!$.fn.DataTable.isDataTable("#noticesTable")) {
                this.dataTable = $("#noticesTable").DataTable();
            }
        });
        await this.fetchNotices();
        // if (this.flashSuccess) {
        //     this.localFlashSuccess = this.flashSuccess;
        //     setTimeout(() => {
        //         this.localFlashSuccess = "";
        //     }, 3000);
        // }
        // if (this.flashError) {
        //     this.localFlashError = this.flashError;
        //     setTimeout(() => {
        //         this.localFlashError = "";
        //     }, 3000);
        // }
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

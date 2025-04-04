import axios from "axios";
import CNotice from "@/components/notice/CNotice.js";
import $ from "jquery";
import moment from "moment-timezone";

export default class CNotices {
    static LOCAL_STORAGE_KEY = "dismissedNotices";
    static stickyNoticeId = null;

    constructor(vueInstance) {
        this.vueInstance = vueInstance
        this.noticesArray = []
    }

    canEdit() {
        return this.vueInstance.user.id == 1;
    }

    canDelete() {
        return this.vueInstance.user.id == 1;
    }

    initializeDataTable() {
        this.vueInstance.$nextTick(() => {
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
                data: this.noticesArray,
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

                            const starIcon = row.form.is_sticky
                                ? '<i class="fas fa-star text-yellow-500"></i>'
                                : '<i class="far fa-star"></i>';

                            return `<span class="cursor-pointer toggle-sticky" data-id="${row.id}">${starIcon}</span>
                                        <span class="text-blue-500 whitespace-nowrap cursor-pointer edit-notice" data-notice='${JSON.stringify(row)}'>${row.form.name || "N/A"}</span>`;
                        }
                    },
                    {
                        data: "description",
                        title: "Description",
                        render: (data, type, row, meta) => row.form.description || "No description"
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
                        data: "scheduled_at",
                        title: "Scheduled At",
                        render: (data, type, row, meta) => {
                            if (row.form.scheduled_at === null) {
                                return `<span class="text-red-500">No Schedule</span>`;
                            }
                            const scheduledAt = moment(row.form.scheduled_at);
                            const timeLeft = scheduledAt.diff(moment(), 'milliseconds');
                            if (timeLeft >= 0) {
                                return `<span class="text-blue-500">Scheduled for ${scheduledAt.format("MMM D, YYYY [at] h:mm A")}</span>`;
                            } else {
                                return `<span class="text-green-500">Published on ${scheduledAt.format("MMM D, YYYY [at] h:mm A")}</span>`;
                            }
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
                        render: (data, type, row, meta) => row.form.created_at ? moment(row.form.created_at).format("MMM D, YYYY [at] h:mm A") : "No Date",
                        orderable: true,
                    },
                    {
                        data: "views_count",
                        title: "Views",
                        render: (data, type, row, meta) => {
                            return `<span class="text-gray-700">${row.form.views_count || 0}</span>`;
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
                            this.vueInstance.toggleSticky(el.dataset.id);
                        });
                    });

                    document.querySelectorAll(".edit-notice").forEach(el => {
                        el.addEventListener("click", () => {
                            this.vueInstance.editNotice(JSON.parse(el.dataset.notice).id);
                        });
                    });

                    document.querySelectorAll(".delete-notice").forEach(el => {
                        el.addEventListener("click", () => {
                            this.vueInstance.deleteNotice(el.dataset.id);
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
    }

    async fetchNotices(url) {
        try {
            const response = await CNotices.fetchNoticesListForAdmin(url, this.vueInstance.NoticeSearchQuery);
            if (response && response.data && Array.isArray(response.data.notices)) {
                this.noticesArray = response.data.notices.map(notice => new CNotice(notice.id, notice));
                const stickyNotice = this.noticesArray.find(notice => notice.form.is_sticky);
                CNotices.stickyNoticeId = stickyNotice ? stickyNotice.form.id : null;
            } else {
                console.error("Invalid API Response Structure:", response.data);
                this.noticesArray = [];
            }

            this.vueInstance.initializeDataTable();
        } catch (error) {
            console.error("Error fetching notices:", error);
        }
    }


    //fetch all notices for the admin
    static async fetchNoticesListForAdmin(url, searchQuery) {
        try {
            return await axios.get(url, {
                params: { search: searchQuery },
            });
        } catch (error) {
            console.error("Error fetching notices:", error);
            return {
                notices: [],
                pagination: null,
            };
        }
    }

    // Fetch unread notices
    static async fetchUnreadNotices(isUserLoggedIn) {
        try {
            const response = await axios.get("/notices/unread");
            let notices = response.data;

            // Handle guest logic
            if (!isUserLoggedIn) {
                const dismissedNotices = this.getDismissedNoticesList();
                notices = this.filterGuestNotices(notices, dismissedNotices);
            }

            return notices;
        } catch (error) {
            console.error("Error fetching unread notices:", error);
            return [];
        }
    }

    //filter the notices for the guest
    static filterGuestNotices(data, dismissedNotice) {
        if (Array.isArray(data)) {
            return data.filter((n) => n.id !== parseInt(dismissedNotice));
        } else if (data && typeof data === "object") {
            return data.id !== parseInt(dismissedNotice) ? [data] : [];
        } else {
            return [];
        }
    }

    // Get dismissed notices from localStorage
    static getDismissedNoticesList() {
        try {
            return (
                JSON.parse(localStorage.getItem(this.LOCAL_STORAGE_KEY)) || []
            );
        } catch {
            return [];
        }
    }

    // Add a notice to the dismissed list in localStorage
    static addDismissedNoticeToStorage(noticeId) {
        const dismissedNotices = this.getDismissedNoticesList();
        if (!dismissedNotices.includes(noticeId)) {
            dismissedNotices.push(noticeId);
            localStorage.setItem(
                this.LOCAL_STORAGE_KEY,
                JSON.stringify(dismissedNotices)
            );
        }
    }
}

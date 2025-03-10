import axios from "axios";
import CNotice from "@/components/notice/CNotice.js";

export default class CNotices {

    static async fetchNoticesListForAdmin(url, searchQuery) {
        try {
            const response = await axios.get(url, {
                params: { search: searchQuery },
            });
            return {
                notices: response.data.notices.map(
                    (noticeJson) => new CNotice(noticeJson.id, noticeJson)
                ),
                pagination: response.data.pagination,
            };
        } catch (error) {
            console.error("Error fetching notices:", error);
            return {
                notices: [],
                pagination: null,
            };
        }
    }

    static async unreadNoticesForUser() {
        try {
            const response = await axios.get("/notices/unread");
            return response.data;
        } catch (error) {
            console.log(error);
            return [];
        }
    }

    static async unreadNoticesForGuest() {
        try {
            const dismissedNotice = localStorage.getItem("dismissedNotice");
            const response = await fetch("/notices");
            const data = await response.json();
            return this.filterGuestNotices(data, dismissedNotice);
        } catch (error) {
            console.log("Error fetching notices", error);
            return [];
        }
    }

    static filterGuestNotices(data, dismissedNotice) {
        if (Array.isArray(data)) {
            return data.filter((n) => n.id !== parseInt(dismissedNotice));
        } else if (data && typeof data === "object") {
            return data.id !== parseInt(dismissedNotice) ? [data] : [];
        } else {
            return [];
        }
    }
}

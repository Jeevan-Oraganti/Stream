import axios from "axios";

export default class CNotices {
    //unreadNoticesForUser
    //unreadNoticesForGuest
    //unreadNotices
    //filterGuestNotices

    static async unreadNoticesForUser() {
        try {
            const response = await axios.get("/notices/unread");
            return response.data;
        } catch (error) {
            console.log(error);
            return [];
        }
    }

    static async fetchNoticesForGuest() {
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

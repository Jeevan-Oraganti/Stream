import axios from "axios";

export default class CNotices {

    //fetch all notices for the admin
    static async fetchNoticesListForAdmin(url, searchQuery) {
        try {
            const response = await axios.get(url, {
                params: {search: searchQuery},
            });
            return response;
        } catch (error) {
            console.error("Error fetching notices:", error);
            return {
                notices: [],
                pagination: null,
            };
        }
    }

    //fetch unread notices for the user who is logged-in
    static async unreadNoticesForUser() {
        try {
            const response = await axios.get("/notices/unread");
            return response.data;
        } catch (error) {
            console.log(error);
            return [];
        }
    }

    //fetch all notices for the user who is not logged-in or guest
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

    //filter the notices for the guest from cookies
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

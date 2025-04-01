import axios from "axios";

export default class CNotices {

    //fetch all notices for the admin
    static async fetchNoticesListForAdmin(url, searchQuery) {
        try {
            return await axios.get(url, {
                params: {search: searchQuery},
            });
        } catch (error) {
            console.error("Error fetching notices:", error);
            return {
                notices: [],
                pagination: null,
            };
        }
    }

    //fetch unread notices
    static async fetchUnreadNotices(isUserLoggedIn) {
        try {
            const response = await axios.get("/notices/unread");
            let notices = response.data;

            // Handle guest logic
            if (!isUserLoggedIn) {
                const dismissedNotice = localStorage.getItem("dismissedNotice");
                notices = this.filterGuestNotices(notices, dismissedNotice);
            }

            return notices;
        } catch (error) {
            console.error("Error fetching unread notices:", error);
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

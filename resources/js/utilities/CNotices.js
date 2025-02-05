import axios from "axios";

export default class CNotices {
    static async unreadNotices() {
        try {
            let notices = [];
            const response = await axios.get("/notices/unread");
            return response.data;
        } catch (error) {
            console.error(error);
            return [];
        }
        return notices;
    }

    static async markAsRead(noticeId) {
        try {
            await axios.post(`/notices/${noticeId}/mark-as-read`);
        } catch (error) {
            console.error(error);
        }
    }

    static async fetchNotices() {
        let notices = [];
        try {
            const dismissedNotice = localStorage.getItem("dismissedNotice");
            const response = await fetch("/notice");
            const data = await response.json();

            if (Array.isArray(data)) {
                notices = data.filter(
                    (n) => n.id !== parseInt(dismissedNotice)
                );
            } else if (data && typeof data === "object") {
                notices = data.id !== parseInt(dismissedNotice) ? [data] : [];
            } else {
                notices = [];
            }
        } catch (error) {
            console.error("Error fetching notices:", error);
            this.notices = [];
        }
        return notices;
    }
}

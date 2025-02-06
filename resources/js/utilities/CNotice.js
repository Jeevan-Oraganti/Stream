import axios from "axios";

export default class CNotice {
    static async unreadNotices() {
        try {
            const response = await axios.get("/notices/unread");
            return response.data;
        } catch (error) {
            console.error(error);
            return [];
        }
    }

    static async acknowledge(noticeId) {
        try {
            await axios.post(`/notices/${noticeId}/acknowledge`);
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

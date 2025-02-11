import axios from "axios";

export default class CNotices {
    static async unreadNotices() {
        try {
            const response = await axios.get("/notices/unread");
            return response.data;
        } catch (error) {
            console.log(error);
            return [];
        }
    }

    static async fetchNotices() {
        try {
            const dismissedNotice = localStorage.getItem("dismissedNotice");
            const response = await fetch("/notice");
            const data = await response.json();

            if (Array.isArray(data)) {
                return data.filter((n) => n.id !== parseInt(dismissedNotice));
            } else if (data && typeof data === 'object') {
                return data.id !== parseInt(dismissedNotice) ? [data] : [];
            } else {
                return [];
            }
        }
        catch (error) {
            console.log("Error fetching notices", error);
            return [];
        }
    }
}

import axios from "axios";
import CForm from "./CForm";
import moment from "moment/moment.js";

export default class CNotice {
    constructor(id = null, data = {}) {
        this.id = id;
        this.form = new CForm([
            "name",
            "description",
            "is_sticky",
            "notice_type_id",
            "expiry_date",
            "created_at",
        ]);

        this.form.data = {
            ...data,
            expiry_date:
                data.expiry_date ||
                moment().add(1, "week").format("YYYY-MM-DD"),
            is_sticky: data.is_sticky || false,
        };
    }

    initializeFromRaw(rawNotice) {
        this.id = rawNotice.id;
        this.form.data = {
            name: rawNotice.name,
            description: rawNotice.description,
            is_sticky: rawNotice.is_sticky,
            notice_type_id: rawNotice.notice_type_id,
            expiry_date: rawNotice.expiry_date,
            created_at: rawNotice.created_at,
        };
    }

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

    async acknowledge() {
        if (!this.id) return;
        try {
            await axios.post(`/notice/${this.id}/acknowledge`);
            console.log(`Notice ${this.id} acknowledged.`);
        } catch (error) {
            console.error("Error acknowledging notice:", error);
        }
    }

    async save() {
        return this.form.save("/admin/notice");
    }

    async update(url) {
        if (!this.id) {
            throw new Error("Cannot update notice without an ID.");
        }
        return this.form.update(url);
    }

    async delete() {
        if (!this.id) {
            throw new Error("Cannot delete notice without an ID.");
        }
        try {
            const response = await axios.delete(`/admin/notice/${this.id}`);
            return response.data;
        } catch (error) {
            console.error("Error deleting notice:", error);
            return [];
        }
    }
}

import axios from "axios";
import CForm from "@/components/notice/CForm.js";
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
            "scheduled_at",
            "is_active",
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
        if (!this.id) throw new Error("Cannot delete notice without an ID.");
        try {
            const response = await axios.delete(`/admin/notice/${this.id}`);
            console.log("Notice deleted:", response.data);
            return response.data;
        } catch (error) {
            console.error(
                "Error deleting notice:",
                error.response?.data || error
            );
            return { success: false, message: "Failed to delete notice." };
        }
    }

    async toggleSticky() {
        if (!this.id) {
            throw new Error("Cannot toggle sticky without an ID.");
        }
        try {
            const response = await axios.post(
                `/admin/notice/${this.id}/toggle-sticky`
            );
            console.log("Notice sticky status toggled:", response.data);
            return response.data;
        } catch (error) {
            console.error(
                "Error toggling sticky status:",
                error.response?.data || error
            );
            return {
                success: false,
                message: "Failed to toggle sticky status.",
            };
        }
    }
}

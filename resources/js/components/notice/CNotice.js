import axios from "axios";
import CForm from "@/components/notice/CForm.js";
import moment from "moment/moment.js";

export default class CNotice {
    //initialize the notice
    constructor(id = null, data = {}) {
        this.id = id;
        this.form = new CForm({
            name: "",
            description: "",
            is_sticky: false,
            notice_type_id: "",
            scheduled_at: moment().startOf("day").add(1, "day").format("YYYY-MM-DD HH:mm"),
            expiry_date: moment().endOf("day").add(2, "day").format("YYYY-MM-DD HH:mm"),
            recurrence: "",
            recurrence_days: [],
            is_active: "",
            created_at: "",
            notice_type: {},
        });

        if (Object.keys(data).length > 0) {
            this.initializeFormData(data);
        }
    }

    initializeFormData(data) {
        //form data
        this.form = new CForm({
            name: data.name,
            description: data.description,
            id: data.id,
            is_sticky: data.is_sticky || false,
            notice_type_id: data.notice_type_id,
            expiry_date: data.expiry_date,
            scheduled_at: data.scheduled_at,
            recurrence: data.recurrence,
            recurrence_days: Array.isArray(data.recurrence_days)
                ? data.recurrence_days
                : [], // Convert to an array if not already
            is_active: data.is_active,
            created_at: data.created_at,
            notice_type: data.notice_type,
        });
    }

    //acknowledge the notice by the logged in user
    async acknowledge() {
        if (!this.id) return;
        try {
            await axios.post(`/notice/${this.id}/acknowledge`);
            console.log(`Notice ${this.id} acknowledged.`);
        } catch (error) {
            console.error("Error acknowledging notice:", error);
        }
    }

    //delete the notice
    async delete() {
        if (!this.id) throw new Error("Cannot delete notice without an ID.");
        try {
            return await axios.post(`/admin/notice/${this.id}/delete`);
        } catch (error) {
            console.error(
                "Error deleting notice:",
                error.response?.data || error
            );
            return { success: false, message: "Failed to delete notice." };
        }
    }

    //toggle the sticky status of the notice making it the priority notice
    async toggleSticky() {
        if (!this.id) {
            throw new Error("Cannot toggle sticky without an ID.");
        }
        try {
            return await axios.post(`/admin/notice/${this.id}/toggle-sticky`);
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

import axios from "axios";
import CForm from "@/components/notice/CForm.js";
import moment from "moment/moment.js";

export default class CNotice {
    //initialize the notice
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

        //set the form data
        this.form.data = {
            ...data,
            expiry_date:
                data.hasOwnProperty("expiry_date")
                    ? data.expiry_date
                    : moment().add(1, "week").format("YYYY-MM-DD"),

            scheduled_at:
                data.hasOwnProperty("scheduled_at")
                    ? data.scheduled_at
                    : moment().add(1, "day").format("YYYY-MM-DD"),

            is_sticky: data.is_sticky || false,
        };

    }

    //acknowledge the notice by the user
    async acknowledge() {
        if (!this.id) return;
        try {
            await axios.post(`/notice/${this.id}/acknowledge`);
            console.log(`Notice ${this.id} acknowledged.`);
        } catch (error) {
            console.error("Error acknowledging notice:", error);
        }
    }

    //save the notice
    async save() {
        return this.form.save("/admin/notice");
    }

    //update the notice
    async update(url) {
        if (!this.id) {
            throw new Error("Cannot update notice without an ID.");
        }
        return this.form.update(url);
    }

    //delete the notice
    async delete() {
        if (!this.id) throw new Error("Cannot delete notice without an ID.");
        try {
            const response = await axios.post(
                `/admin/notice/${this.id}/delete`
            );
            console.log("Notice deleted:", response);
            return response;
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
            const response = await axios.post(
                `/admin/notice/${this.id}/toggle-sticky`
            );
            console.log("Notice sticky status toggled:", response.data);
            return response;
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

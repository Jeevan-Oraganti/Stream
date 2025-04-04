import axios from "axios";
import CForm from "@/components/notice/CForm.js";
import moment from "moment/moment.js";
import CNotices from "./CNotices";

export default class CNotice {
    //initialize the notice
    constructor(id = null, data = {}) {
        this.id = id;
        this.form = new CForm({
            name: "",
            description: "",
            is_sticky: false,
            notice_type_id: "",
            event_date: moment()
                .startOf("day")
                .add(1, "day")
                .format("YYYY-MM-DD HH:mm"),
            expiry_date: moment()
                .endOf("day")
                .add(1, "day")
                .format("YYYY-MM-DD HH:mm"),
            recurrence: "",
            recurrence_days: [],
            is_active: "",
            created_at: "",
            notice_type: {},
            views_count: 0,
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
            event_date: data.event_date,
            recurrence: data.recurrence,
            recurrence_days: Array.isArray(data.recurrence_days)
                ? data.recurrence_days
                : [], // Convert to an array if not already
            is_active: data.is_active,
            created_at: data.created_at,
            notice_type: data.notice_type,
            views_count: data.views_count,
        });
    }

    // Determine if guest or logged-in user
    // For Guest
    //     use localstorage OR cookies
    // For Logged-in user
    //     neither.

    //acknowledge the notice by the logged-in user
    async noticeDismissedByUser(isUserLoggedIn) {
        // Check if the user is logged in
        if (!isUserLoggedIn) {
            // For guests, use localStorage to track acknowledged notices
            CNotices.addDismissedNoticeToStorage(this.id);
            console.log(`Notice ${this.id} dismissed for guest.`);
            return;
        }
        if (!this.id) return;
        try {
            await axios.post(`/notice/${this.id}/acknowledge`);
            console.log(`Notice ${this.id} acknowledged.`);
        } catch (error) {
            console.error("Error acknowledging notice:", error);
        }
    }

    async createOrUpdate(vueInstance) {
        if (vueInstance.loading) return;

        if (this.form.is_sticky && CNotices.stickyNoticeId && CNotices.stickyNoticeId !== this.form.id) {
            vueInstance.loading = false
            vueInstance.$modal.show('confirmation-modal');
            return;
        }

        try {
            vueInstance.loading = true
            await this.proceedWithSave(vueInstance)
        } catch (error) {
            console.error('Error saving notice:', error);
        } finally {
            vueInstance.loading = false;
        }

    }

    async proceedWithSave(vueInstance) {
        try {
            await this.form.createOrUpdate('/admin/notice/createOrUpdate')
            vueInstance.$parent.localFlashSuccess = this.form.id ? 'Notice updated successfully' : 'Notice saved successfully!';
            vueInstance.$modal.hide("add-edit-notice");
            await vueInstance.$parent.fetchNotices();
        } catch (error) {
            console.error('Error saving notice:', error);
        }
    }

    //delete the notice
    delete(vueInstance) {
        if (!this.id) throw new Error("Cannot delete notice without an ID.");

        if (confirm("Are you sure you want to delete this notice?")) {
            axios.post(`/admin/notice/${this.id}/delete`).then((res) => {
                vueInstance.localFlashSuccess = "Notice deleted successfully!";
                vueInstance.notices.noticesArray = vueInstance.notices.noticesArray.filter(n => n.id !== this.id)
                vueInstance.initializeDataTable()

            }).catch((err) => {
                if (err.response && err.response.status === 403) {
                    vueInstance.localFlashError = "You are not authorized to delete this notice!";
                } else if (err.response.status === 404) {
                    vueInstance.localFlashError = "Notice Not Found!";
                } else {
                    vueInstance.localFlashError = "An error occurred while deleting the notice.";
                }
            })
        }
    }

    //toggle the sticky status of the notice making it the priority notice
    async toggleSticky(vueInstance) {
        if (!this.id) {
            throw new Error("Cannot toggle sticky without an ID.");
        }

        vueInstance.notices.noticesArray.forEach((notice) => {
            if (notice.form.is_sticky && notice.id != this.id) {
                notice.form.is_sticky = false
            }
        });

        this.form.is_sticky = !this.form.is_sticky
        vueInstance.initializeDataTable()

        axios.post(`/admin/notice/${this.id}/toggle-sticky`).then((res) => {
            vueInstance.localFlashSuccess = "Notice updated successfully!";
        }).catch((err) => {
            vueInstance.localFlashError = "An error occurred while updating the notice.";
        })
    }

    convertDateTime(dateTime) {
        // Convert format to YYYY-MM-DDTHH:MM for datetime-local input
        const utcDate = new Date(dateTime);

        // Convert to IST timezone
        const istDate = new Intl.DateTimeFormat("en-CA", {
            timeZone: "Asia/Kolkata",
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
            hour12: false
        }).format(utcDate);

        return istDate.replace(",", "").replace(/\//g, "-").replace(" ", "T")
    }

    updateDateTime() {
        if (this.form.expiry_date) {
            this.form.expiry_date = this.convertDateTime(this.form.expiry_date);
        }
        if (this.form.event_date) {
            this.form.event_date = this.convertDateTime(this.form.event_date);
        }
    }

    undoChangesFromClone(clonedNotice, vueInstance) {
        for (let key in this.form) {
            this.form[key] = clonedNotice.form[key]
        }

        vueInstance.$parent.initializeDataTable()
    }

    clearError(field, vueInstance) {
        if (this.form.hasError(field)) {
            vueInstance.$delete(this.form.errors, field);
        }
    }
}

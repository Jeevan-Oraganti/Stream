import axios from "axios";
import CForm from "./CForm.js";

export default class CAdminNotice {
    constructor() {
        this.form = new CForm([
            "name",
            "description",
            "notification_type_id",
            "expiry_date",
        ]);
    }

    // Create new CForm
    // CNotice creates a instance of CForm with required fields
    // CNotice has save
    //     save returns promise
    //         then or error
    // Create a new dedicated page for the listing/add/removing the notices

    async save() {
        console.log('save called');
        return this.form.save("/admin/notice")
            .then((data) => {
                console.log("Notice saved successfully:", data);
                return data;
            })
            .catch((error) => {
                console.error("Error saving notice:", error);
                return Promise.reject(error);
            });
    }

    async delete(id) {
        try {
            await axios.delete(`/admin/notice/${id}`);
            console.log(`Notice ${id} deleted.`);
        } catch (error) {
            console.error("Error deleting notice:", error);
        }
    }
}

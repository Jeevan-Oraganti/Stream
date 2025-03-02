import axios from "axios";
import CForm from "./CForm";
import moment from "moment/moment.js";

export default class CNoticesAdmin {
    constructor(id = null, data = {}) {
        this.id = id;
        this.form = new CForm([
            "name",
            "description",
            "is_sticky",
            "notice_type_id",
            "expiry_date",
        ]);

        this.form.data = {
            ...data,
            expiry_date:
                data.expiry_date ||
                moment().add(1, "week").format("YYYY-MM-DD"),
            is_sticky: data.is_sticky || false,
        };
    }

    async save() {
        return this.form
            .save("/admin/notice")
            .then((data) => {
                this.id = data.id;
                return data;
            })
            .catch((error) => Promise.reject(error));
    }

    async delete() {
        if (!this.id) {
            console.error("Error: Notice ID is null or undefined.");
            return;
        }

        try {
            await axios.delete(`/admin/notice/${this.id}`);
            console.log(`Notice ${this.id} deleted.`);
        } catch (error) {
            console.error("Error deleting notice:", error);
        }
    }
}

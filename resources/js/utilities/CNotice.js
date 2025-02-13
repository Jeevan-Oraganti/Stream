import axios from "axios";

export default class CNoticeUser {
    constructor(id = null, data = {}) {
        this.id = id;
        this.name = data.name;
        this.description = data.description;
        this.created_at = data.created_at;
        this.notification_type_id = data.notification_type_id;
        this.expiry_date = data.expiry_date;
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
}

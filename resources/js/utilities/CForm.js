import axios from "axios";

export default class CForm {
    constructor(fields) {
        this.fields = fields;
        this.errors = {};
        this.data = {};

        fields.forEach((field) => {
            this.data[field] = "";
        });
    }

    async save(url) {
        try {
            const response = await axios.post(url, this.data);
            this.clear();
            return response;
        } catch (error) {
            this.errors = error.response.data.errors || {};
            throw error;
        }
    }

    clear() {
        this.fields.forEach((field) => {
            this.data[field] = "";
        });
        this.errors = {};
    }
}

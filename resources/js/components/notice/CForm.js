import axios from "axios";

export default class CForm {
    constructor(fields) {
        this.originalData = {};
        this.errors = {};

        for(let field in fields) {
            this.originalData[field] = fields[field];
            this[field] = fields[field]
        }
    }

    reset() {
        Object.assign(this.data, this.originalData);
        this.errors = {};
    }

    async saveOrUpdate(url) {
        try {
            const response = await axios.post(url, this);
            // this.reset();
            return response.data;
        } catch (error) {
            this.handleErrors(error);
            throw error;
        }
    }

    hasError(field) {
        return this.errors.hasOwnProperty(field);
    }

    getError(field) {
        return this.hasError(field) ? this.errors[field][0] : null;
    }

    handleErrors(error) {
        if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
        } else {
            console.error("An error occurred:", error);
        }
    }
}

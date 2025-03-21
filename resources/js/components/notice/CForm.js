import axios from "axios";

export default class CForm {
    constructor(fields) {
        // Initializes the form with given fields, setting up original data, current data, and errors.
        this.originalData = {};
        this.data = {};
        this.errors = {};

        fields.forEach((field) => {
            this.originalData[field] = "";
            this.data[field] = "";
        });
    }

    // Resets the form data to its original state and clears errors.
    reset() {
        Object.assign(this.data, this.originalData);
        this.errors = {};
    }

    // Sends a POST request to save the form data to the given URL and resets the form on success.
    async save(url) {
        try {
            const response = await axios.post(url, this.data);
            this.reset();
            return response.data;
        } catch (error) {
            this.handleErrors(error);
            throw error;
        }
    }

    // Sends a POST request to update the form data at the given URL and resets the form on success.
    async update(url) {
        try {
            const response = await axios.post(url, this.data);
            this.reset();
            return response.data;
        } catch (error) {
            this.handleErrors(error);
            throw error;
        }
    }

    // Checks if there is an error for the specified field.
    hasError(field) {
        return this.errors.hasOwnProperty(field);
    }

    // Retrieves the first error message for the specified field, if any.
    getError(field) {
        return this.hasError(field) ? this.errors[field][0] : null;
    }

    // Handles errors from the server, storing validation errors if the status is 422.
    handleErrors(error) {
        if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
        } else {
            console.error("An error occurred:", error);
        }
    }
}

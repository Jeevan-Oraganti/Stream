<template>
    <div>
        <div class="container py-4 px-16 bg-gray-900 text-white rounded-lg">
            <h1 class="text-2xl text-center font-semibold mb-6 mt-6">Contact Us</h1>

            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label for="name" class="block text-lg text-gray-200 mb-2">Your Name</label>
                    <input type="text" id="name" v-model="form.name"
                        class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-md"
                        placeholder="Your Name" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-lg text-gray-200 mb-2">Your Email</label>
                    <input type="email" id="email" v-model="form.email"
                        class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-md"
                        placeholder="Your Email" required>
                </div>

                <div class="mb-4">
                    <label for="message" class="block text-lg text-gray-200 mb-2">Your Message</label>
                    <textarea id="message" v-model="form.message"
                        class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-600 rounded-md"
                        placeholder="Your Message" rows="4" required></textarea>
                </div>

                <div class="flex justify-between mb-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                        Send Message
                    </button>

                    <a @click="cancel"
                        class="button text-white border-2 border-white hover:bg-white hover:text-gray-900 px-4 py-2 rounded-md">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: '',
                email: '',
                message: ''
            }
        };
    },
    methods: {
        submitForm() {
            console.log('Form submitted:', this.form);
            alert('Thank you for contacting us! We will get back to you soon.');
            this.resetForm();
        },

        cancel() {
            this.$modal.hide('contact-modal');
            this.resetForm();
        },

        resetForm() {
            this.form.name = '';
            this.form.email = '';
            this.form.message = '';
        }
    }
}
</script>

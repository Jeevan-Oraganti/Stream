<template>
    <div>
        <a class="text-blue-100 hover:text-white" @click="$modal.show('contact-support-modal')">
            Support
        </a>

        <modal name="contact-support-modal" height="auto" width="50%" :pivotY=".5" class="rounded-none shadow-inner" :styles="{ backgroundColor: 'transparent' }">
            <div class="container mx-auto py-6 bg-gray-900 text-white">
                <h1 class="text-2xl text-center font-semibold mb-6">Have a Question?</h1>

                <form autocomplete="off" class="p-6 bg-gray-900" @submit.prevent="contactSupport"
                    @keydown="submitted = false">
                    <div class="control mb-4">
                        <label for="name" class="block text-white mb-2">What's your name?</label>
                        <input type="text" name="name" id="name"
                            class="input w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded"
                            placeholder="Your name" v-model="message.name" @keydown="delete errors.name" required>
                        <span class="text-xs text-red-500 pt-2" v-text="errors.name[0]" v-if="errors.name"></span>
                    </div>

                    <div class="control mb-4">
                        <label for="email" class="block text-white mb-2">Which email address should we respond
                            to?</label>
                        <input type="email" name="email" id="email"
                            class="input w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded"
                            placeholder="Your email" v-model="message.email" @keydown="delete errors.email" required>
                        <span class="text-xs text-red-500 pt-2" v-text="errors.email[0]" v-if="errors.email"></span>
                    </div>

                    <div class="control mb-4">
                        <label for="body" class="block text-white mb-2">What's on your mind?</label>
                        <textarea name="question" id="body"
                            class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded"
                            placeholder="Your question" v-model="message.question" @keydown="delete errors.question"
                            required></textarea>
                        <span class="text-xs text-red-500 pt-2" v-text="errors.question[0]"
                            v-if="errors.question"></span>
                    </div>

                    <div class="control mb-4">
                        <label for="verification" class="block text-white mb-2">What's 2 + 2?</label>
                        <input type="text" name="verification" id="verification"
                            class="input w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded"
                            placeholder="Your answer" v-model="message.verification"
                            @keydown="delete errors.verification" required>
                        <span class="text-xs text-red-500 pt-2" v-text="errors.verification[0]"
                            v-if="errors.verification"></span>
                    </div>

                    <div class="control flex justify-end space-x-4">
                        <a @click="cancel"
                            class="button text-white border-2 border-white hover:bg-white hover:text-gray-900 px-4 py-2 rounded">
                            Cancel
                        </a>
                        <button type="submit" class="button bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
                            :disabled="submitted">
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: {},
            errors: {},
            submitted: false
        }
    },
    methods: {

        cancel() {
            this.$modal.hide('contact-support-modal');
            this.resetForm();
        },

        contactSupport() {
            this.submitted = true;
            console.log(this.message);

            axios
                .post('/contact', this.message)
                .then(() => {
                    this.$modal.hide('contact-support-modal');

                    this.resetForm();

                    swal('Success', 'Your message has been sent!', 'success');
                })
                .catch(errors => {
                    this.errors = errors.response.data.errors;
                });
        },

        resetForm() {
            this.message = {};
            this.errors = {};
            this.submitted = false;
        }
    }
}
</script>

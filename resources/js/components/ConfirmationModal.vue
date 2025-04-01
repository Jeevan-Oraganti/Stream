<!-- filepath: /home/bnetworks/websites/stream/resources/js/components/ConfirmationModal.vue -->
<template>
    <modal name="confirmation-modal" height="auto" :pivotY=".5" class="rounded-lg shadow-lg w-full max-w-lg mx-auto px-4">
        <div class="container block m-auto justify-center p-6 bg-white rounded-lg shadow-md">
            <div class="border-2 py-5 px-5 rounded-md">
                <h2 class="text-xl font-bold text-gray-900 mb-4 text-center">{{ title }}</h2>
                <p class="text-sm text-gray-700 mb-6 text-center">{{ message }}</p>
                <div class="flex justify-center space-x-4">
                    <button
                        @click="cancel"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-200 ease-in-out">
                        Cancel
                    </button>
                    <button
                        @click="confirm"
                        class="px-5 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition duration-200 ease-in-out">
                        Proceed
                    </button>
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: "Confirm Action",
        },
        message: {
            type: String,
            default: "Are you sure you want to proceed?",
        },
    },
    data() {
        return {
            visible: false,
        };
    },
    methods: {
        confirm() {
            this.$emit("confirm");
            this.$modal.hide("confirmation-modal");
        },
        cancel() {
            this.$emit("cancel");
            this.$modal.hide("confirmation-modal");
        },
    },
};
</script>

<style scoped>
/* Add any custom styles here */
</style>

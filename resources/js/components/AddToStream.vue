<template>
    <div>
        <div id="push-to-stream" class="border border-gray-500 mb-12 rounded-xl p-4 rounded-lg mb-4 rounded-lg">
            <div class="font-bold text-lg text-gray-100 mb-4">
                Push to the Stream...
            </div>
            <div class="message-body">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                    <!-- Textarea -->
                    <p class="control">
                        <textarea
                            class="textarea w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500"
                            placeholder="I have something to say..."
                            v-model="form.body">
                        </textarea>
                        <span
                            class="help text-red-500 text-sm mt-1 block"
                            v-if="form.errors.has('body')"
                            v-text="form.errors.get('body')">
                        </span>
                    </p>

                    <!-- Submit Button -->
                    <p class="control mt-4">
                        <button
                            class="button bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 disabled:opacity-50"
                            :disabled="form.errors.any()">
                            Submit
                        </button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>


<script>
import { formToJSON } from 'axios';

export default {
    data() {
        return {
            form: new Form({ body: '' })
        };
    },
    methods: {
        onSubmit() {
            this.form.post('/statuses').then(status => this.$emit('completed', status));
        }
    }
}
</script>

<!--<style>-->
<!--textarea::placeholder {-->
<!--    color: #888 !important;-->
<!--}-->

<!--textarea {-->
<!--    color: #333;-->
<!--}-->
<!--</style>-->

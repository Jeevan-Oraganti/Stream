<template>
    <div>
        <div class="message">
            <div class="message-header">
                Push to the Stream...
            </div>
            <div class="message-body">
                <form @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                    <p class="control">
                        <textarea class="textarea" placeholder="I have something to say..."
                            v-model="form.body"></textarea>
                        <span class="help is-danger" v-if="form.errors.has('body')"
                            v-text="form.errors.get('body')"></span>
                    </p>

                    <p class="control mt-4">
                        <button class="button is-primary" :disabled="form.errors.any()">Submit</button>
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
            console.log(this.form.data());
            this.form.post('/statuses').then(status => this.$emit('completed', status));
        }
    }
}
</script>

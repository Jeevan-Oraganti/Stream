<!-- filepath: /home/bnetworks/websites/stream/resources/js/components/notice/admin/NoticeForm.vue -->
<template>
    <modal name="add-edit-notice" @opened="logging" height="auto" :pivotY=".5" class="rounded-lg shadow-lg w-full max-w-lg mx-auto px-4">
        <div class="container block m-auto justify-center p-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">{{ form.id ? 'Edit Notice' : 'Add New Notice' }}</h2>
            <div v-if="localFlashSuccess" class="notification is-success">
                {{ localFlashSuccess }}
            </div>
            <div v-if="localFlashError" class="notification is-danger">
                {{ localFlashError }}
            </div>
            <form @submit.prevent="saveNotice" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Notice Title</label>
                    <input v-model="form.data.name" type="text"
                        class="text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('name') }" @input="clearError('name')">
                    <span v-if="form.hasError('name')" class="text-red-500 text-sm mt-1 block">{{ form.getError('name')
                        }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.data.description"
                        class="text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('description') }"
                        @input="clearError('description')"></textarea>
                    <span v-if="form.hasError('description')" class="text-red-500 text-sm mt-1 block">{{
                        form.getError('description') }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Notice Type</label>
                    <select v-model="form.data.notice_type_id"
                        class="text-gray-500 w-full p-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('notice_type_id') }"
                        @change="clearError('notice_type_id')">
<!--                        <option v-for="type in form.data.notice_type" :key="type.id" :value="type.id">{{ type.name }}</option>-->
                        <option value="1">Announcement</option>
                        <option value="2">Information</option>
                        <option value="3">Outage</option>
                    </select>
                    <span v-if="form.hasError('notice_type_id')" class="text-red-500 text-sm mt-1 block">{{
                        form.getError('notice_type_id') }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                    <input v-model="form.data.expiry_date" type="datetime-local"
                        class="text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('expiry_date') }" @input="clearError('expiry_date')">
                    <span v-if="form.hasError('expiry_date')" class="text-red-500 text-sm mt-1 block">{{
                        form.getError('expiry_date') }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Scheduled At</label>
                    <input v-model="form.data.scheduled_at" type="datetime-local"
                        class="text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        :class="{ 'border-red-500': form.hasError('scheduled_at') }"
                        @input="clearError('scheduled_at')">
                    <span v-if="form.hasError('scheduled_at')" class="text-red-500 text-sm mt-1 block">{{
                        form.getError('scheduled_at') }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Priority Notice</label>
                    <div class="flex items-center">
                        <i :class="{ 'fas fa-star text-yellow-500': form.data.is_sticky, 'far fa-star text-gray-500': !form.data.is_sticky }"
                            @click="form.data.is_sticky = !form.data.is_sticky; clearError('is_sticky')"
                            class="cursor-pointer text-2xl"></i>
                        <span class="ml-2 text-gray-700">Make this a priority notice</span>
                    </div>
                    <span v-if="form.hasError('is_sticky')" class="text-red-500 text-sm mt-1 block">{{
                        form.getError('is_sticky') }}</span>
                </div>
                <div class="flex mt-6 justify-between space-x-3">
                    <button type="button" @click="$modal.hide('add-edit-notice')"
                        class="w-full bg-indigo-500 text-white py-2 rounded-md hover:bg-indigo-600 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="w-full bg-indigo-500 text-white py-2 rounded-md hover:bg-indigo-600 transition duration-200">
                        {{ form.id ? 'Update Notice' : 'Add Notice' }}
                    </button>
                </div>
            </form>
        </div>
    </modal>
</template>

<script>
import CNotice from "@/components/notice/CNotice.js";

export default {
    props: {
        notice: {
            type: Object,
            default: null,
        },
        flashSuccess: {
            type: String,
            default: ''
        },
        flashError: {
            type: String,
            default: ''
        },
    },
    data() {
        return {
            form: new CNotice().form,
            errors: {},
            loading: false,
            localFlashSuccess: '',
            localFlashError: '',
        };
    },
    methods: {
        logging() {
          console.log(this.form.data);
        },
        async saveNotice() {
            console.log(this.form.data);
            try {
                if (this.loading) return;
                this.startLoading();
                if (this.form.id) {
                    await this.form.update(`/admin/notice/${this.form.id}`);
                } else {
                    await this.form.save('/admin/notice');
                }
                this.localFlashSuccess = "Notice edited successfully!";
                this.$modal.hide("add-edit-notice");

                this.stopLoading();
                // this.$emit('notice-saved', response.data.notice);
                window.location.href = '/admin/notices';

            } catch (error) {
                this.localFlashError = "Error saving the notice";
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Error saving notice:', error);
                }
                this.stopLoading();

                if (this.localFlashError) {
                    setTimeout(() => {
                        this.localFlashError = '';
                    }, 3000);
                }
            } finally {
                this.loading = false;
            }
        },
        startLoading() {
            this.loading = true;
            this.progress = 0;
            this.interval = setInterval(() => {
                if (this.progress < 95) {
                    this.progress += 5;
                }
            }, 100);
        },
        stopLoading() {
            clearInterval(this.interval);
            this.progress = 100;
            setTimeout(() => {
                this.loading = false;
                this.progress = 0;
            }, 500);
        },
        clearError(field) {
            if (this.form.hasError(field)) {
                this.$delete(this.form.errors, field);
            }
        },
    },
    watch: {
        notice: {
            immediate: true,
            handler(newNotice) {
                if (newNotice) {
                    this.form.data = new CNotice(newNotice.id, newNotice).form.data;
                    this.form.id = newNotice.id;
                    if (this.form.data.expiry_date) {
                        this.form.data.expiry_date = new Date(this.form.data.expiry_date).toISOString().slice(0, 16);
                    }
                    if (this.form.data.scheduled_at) {
                        this.form.data.scheduled_at = new Date(this.form.data.scheduled_at).toISOString().slice(0, 16);
                    }
                } else {
                    const now = new Date();
                    const nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
                    this.form.data.expiry_date = nextWeek.toISOString().slice(0, 16);
                }

                this.localFlashSuccess = this.flashSuccess;
                this.localFlashError = this.flashError;
            }
        }
    },
};
</script>

<style scoped>
.loader {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 25px;
    height: 25px;
    border: 4px solid rgba(255, 255, 255, 0.2);
    border-top-color: rgba(37, 197, 239, 1);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.notification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    width: auto;
    padding: 1rem;
    border-radius: 0.25rem;
    font-size: 1rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.notification.is-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.notification.is-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

/* The slider */
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input:checked+.slider {
    background-color: #2196F3;
}

input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}
</style>

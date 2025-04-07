<!-- filepath: /home/bnetworks/websites/stream/resources/js/components/notice/admin/NoticeForm.vue -->
<template>
    <modal name="add-edit-notice" height="auto" @opened="opened" @closed="closed"
        class="rounded-lg w-full max-w-lg mx-auto px-4">
        <div class="container block m-auto justify-center p-8 bg-white">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">{{ notice.form.id ? 'Edit Notice' : 'Add New Notice' }}
            </h2>
            <form @submit.prevent="createOrUpdateNotice" class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Notice Title</label>
                    <input v-model="notice.form.name" type="text"
                        class="text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        @input="clearError('name')">
                    <span v-if="notice.form.hasError('name')" class="text-red-500 text-sm mt-1 block">{{
                        notice.form.getError('name')
                        }}</span>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="notice.form.description"
                        class="text-gray-800 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        @input="clearError('description')"></textarea>
                    <span v-if="notice.form.hasError('description')" class="text-red-500 text-sm mt-1 block">{{
                        notice.form.getError('description')
                        }}</span>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Notice Type</label>
                    <select v-model="notice.form.notice_type_id"
                        class="text-gray-500 w-full p-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
                        @change="clearError('notice_type_id')">
                        <option value="1">Announcement</option>
                        <option value="2">Information</option>
                        <option value="3">Outage</option>
                        <option value="4">Holiday</option>
                    </select>
                    <span v-if="notice.form.hasError('notice_type_id')" class="text-red-500 text-sm mt-1 block">{{
                        notice.form.getError('notice_type_id')
                        }}</span>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Event Date</label>
                    <input v-model="notice.form.event_date" type="datetime-local"
                        class="text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        @input="clearError('event_date')" @change="syncExpiryDate">
                    <span v-if="notice.form.hasError('event_date')" class="text-red-500 text-sm mt-1 block">{{
                        notice.form.getError('event_date')
                        }}</span>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Expiry Date</label>
                    <input v-model="notice.form.expiry_date" type="datetime-local"
                        class="text-gray-500 w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        @input="clearError('expiry_date')">
                    <span v-if="notice.form.hasError('expiry_date')" class="text-red-500 text-sm mt-1 block">{{
                        notice.form.getError('expiry_date')
                        }}</span>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Recurrence</label>
                    <select v-model="notice.form.recurrence" @change="clearSelection"
                        class="text-gray-500 w-full p-2 border rounded-md bg-white">
                        <option value="">None</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>

                    <div v-if="notice.form.recurrence === 'weekly'" class="mt-2">
                        <label class="text-sm font-medium text-gray-700">Days of the Week</label>
                        <div class="flex flex-wrap space-x-2 mt-1">
                            <label v-for="(day, index) in weekdays" :key="index" class="flex items-center space-x-1">
                                <input type="checkbox" :value="day" :checked="isChecked(day)" @change="toggleDay(day)"
                                    class="mr-1" />
                                {{ day }}
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Priority Notice</label>
                    <div class="flex items-center">
                        <i :class="{ 'fas fa-star text-yellow-500': notice.form.is_sticky, 'far fa-star text-gray-500': !notice.form.is_sticky }"
                            @click="toggleSticky" class="cursor-pointer text-2xl"></i>
                        <span class="ml-2 text-gray-700 cursor-pointer" @click="toggleSticky">Make this a priority notice</span>
                    </div>
                </div>
                <div class="flex mt-4 justify-between space-x-3">
                    <button type="button" @click="$modal.hide('add-edit-notice')"
                        class="w-full bg-indigo-500 text-white py-2 rounded-md hover:bg-indigo-600 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="w-full bg-indigo-500 text-white py-2 rounded-md hover:bg-indigo-600 transition duration-200">
                        {{ notice.id ? 'Update Notice' : 'Add Notice' }}
                    </button>
                </div>
            </form>
        </div>
        <!-- Confirmation Modal -->
        <confirmation-modal title="Warning"
            message="There is already an existing sticky notice available. Proceeding will unstick the previous one. Do you want to continue?"
            @confirm="proceedWithSave">
        </confirmation-modal>
    </modal>
</template>

<script>
import ConfirmationModal from "@/components/ConfirmationModal.vue";
import moment from "moment";

export default {
    components: { ConfirmationModal },
    props: {
        notice: {
            type: Object,
            default: null,
        }
    },
    data() {
        return {
            clonedNotice: null,
            loading: false,
            weekdays: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
        };
    },
    methods: {
        syncExpiryDate() {
            if (this.notice.form.event_date) {
                this.notice.form.expiry_date = moment(this.notice.form.event_date)
                    .set({ hour: 23, minute: 59, second: 0 })
                    .format("YYYY-MM-DD HH:mm");
            }
        },
        clearSelection() {
            if (this.notice.form.recurrence !== "weekly") {
                this.notice.form.recurrence_days = [];
            }
        },
        isChecked(day) {
            return this.notice.form.recurrence_days.includes(day);
        },
        toggleDay(day) {
            const index = this.notice.form.recurrence_days.indexOf(day);
            if (index > -1) {
                this.notice.form.recurrence_days.splice(index, 1);
            } else {
                this.notice.form.recurrence_days.push(day);
            }
        },
        toggleDaySelection(day) {
            const index = this.notice.form.recurrence_days.indexOf(day);
            if (index === -1) {
                this.notice.form.recurrence_days.push(day);
            } else {
                this.notice.form.recurrence_days.splice(index, 1);
            }
        },
        // Toggles the `is_sticky` property of the notice form between true and false
        toggleSticky() {
            this.notice.form.is_sticky = !this.notice.form.is_sticky;
        },
        //creates a deep copy of `notice` to allow undoing changes
        opened() {
            this.clonedNotice = JSON.parse(JSON.stringify(this.notice));
        },
        //restores `notice` from the cloned copy to undo changes
        closed() {
            this.notice.undoChangesFromClone(this.clonedNotice, this);
        },
        //handling creation or updating logic
        async createOrUpdateNotice() {
            await this.notice.createOrUpdate(this);
        },
        //handling the final save operation
        async proceedWithSave() {
            await this.notice.proceedWithSave(this);
        },
        cancelSave() {
            this.$modal.hide("confirmation-modal");
        },
        // Clears validation errors for a specific field
        clearError(field) {
            this.notice.clearError(field, this);
        },
    },
    watch: {
        // Watches for changes in `notice` and re-initializes the DataTable when changes occur
        'notice': {
            handler(newVAl, oldVAl) {
                this.$parent.initializeDataTable();
            },
            deep: true // Enables deep watching to detect changes within nested properties
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

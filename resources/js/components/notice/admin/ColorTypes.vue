<!-- filepath: /home/bnetworks/websites/stream/resources/js/components/notice/admin/ColorTypes.vue -->
<template>
    <modal name="change-notice-type-color" height="auto" :pivotY=".5"
           class="rounded-lg w-full max-w-lg mx-auto px-4">
        <div class="py-4">
            <h2 class="text-2xl mt-4 font-bold mb-6 text-gray-800 text-center">Change Notice Type Color</h2>

            <!-- Success & Error Messages -->
            <div v-if="localFlashSuccess" class="notification is-success">
                {{ localFlashSuccess }}
            </div>
            <div v-if="localFlashError" class="notification is-danger">
                {{ localFlashError }}
            </div>

            <div class="bg-white rounded-lg p-4">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border">
                                Notice Types
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border">
                                Change Color
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="type in noticeTypes" :key="type.id" :style="{ backgroundColor: type.color + '50' }"
                            class="hover:bg-gray-100 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border">
                                {{ type.type.charAt(0).toUpperCase() + type.type.slice(1) }}
                            </td>
                            <td class="px-6 py-4 items-center whitespace-nowrap border">
                                <div class="flex items-center space-x-4">
                                    <!-- Color Selection -->
                                    <swatches v-model="type.color" :swatches="colors" row-length="3"
                                        @input="changeColor(type)" show-fallback fallback-input-type="color"
                                        styles="width: '138px', height: '169px'">
                                    </swatches>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </modal>
</template>

<script>
import axios from 'axios';
import Swatches from 'vue-swatches';
import 'vue-swatches/dist/vue-swatches.css';
import _ from "lodash";

export default {
    components: { Swatches },
    data() {
        return {
            noticeTypes: [],
            localFlashSuccess: '',
            localFlashError: '',
            colors: [
                // Announcement (Red/Pink)
                ['#D91E36', '#E63946', '#EE6B6E'],

                // Information (Blue)
                ['#0E4DA4', '#2A69C0', '#4C89DD'],

                // Outage (Amber/Yellow)
                ['#C97B00', '#E39200', '#F5B700'],

                // Holiday (Green)
                ['#1B5E20', '#388E3C', '#66BB6A'],
            ]
        };
    },
    methods: {
        // Fetches the list of notice types from the server and updates the local data
        async fetchNoticeTypes() {
            try {
                const response = await axios.get('/admin/notice-types');
                this.noticeTypes = response.data.noticeTypes.map(type => ({ ...type }));
            } catch (error) {
                console.error('Error fetching notice types:', error);
            }
        },
        // Updates the color of a specific notice type and sends the change to the server
        changeColor: _.debounce(function (type) {
            try {
                axios.post(`/admin/notice-type/${type.id}/change-color`, {
                    color: type.color
                });
                this.localFlashSuccess = 'Notice type color updated successfully!';
                const noticeIndex = this.noticeTypes.findIndex(n => n.id === type.id);
                if (noticeIndex !== -1) {
                    this.noticeTypes[noticeIndex].color = type.color;
                }
                this.$emit('color-updated');
                setTimeout(() => {
                    this.localFlashSuccess = '';
                }, 3000);
            } catch (error) {
                console.error('Error changing color:', error);
                this.localFlashError = 'An error occurred while changing the color.';
                setTimeout(() => {
                    this.localFlashError = '';
                }, 3000);
            }
        }, 500)
    },
    // Fetches the notice types when the component is mounted
    async mounted() {
        await this.fetchNoticeTypes();
    }
};
</script>

<style scoped>
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

::v-deep .vm--modal {
    overflow: visible !important;
}
</style>

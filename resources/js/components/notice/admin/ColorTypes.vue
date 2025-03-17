<!-- filepath: /home/bnetworks/websites/stream/resources/js/components/notice/admin/ColorTypes.vue -->
<template>
    <div class="container mx-auto p-8 min-h-screen">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Change Notice Type Color</h2>

        <!-- Success & Error Messages -->
        <div v-if="localFlashSuccess" class="notification is-success">
            {{ localFlashSuccess }}
        </div>
        <div v-if="localFlashError" class="notification is-danger">
            {{ localFlashError }}
        </div>

        <div class="bg-white overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead>
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border">
                            Notice Types
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border">
                            Current
                            Color
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border">
                            Change
                            Color
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="type in noticeTypes" :key="type.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border">{{
                            type.type.charAt(0).toUpperCase() + type.type.slice(1)
                        }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap border">
                            <div :style="{ backgroundColor: type.color }" class="w-24 h-6 rounded-md border"></div>
                            <span class="text-sm font-semibold" :style="{ color: type.color }">{{
                                type.color.charAt(0).toUpperCase() + type.color.slice(1)
                                }}</span>
                        </td>
                        <td class="px-6 py-4 items-center whitespace-nowrap border">
                            <div class="flex items-center space-x-6">
                                <!-- Color Selection -->
                                <div class="relative mt-2">
                                    <v-swatches v-model="type.newColor" @input="updateColor(type, type.newColor)"
                                        show-fallback fallback-input-type="color">
                                    </v-swatches>
                                </div>

                                <!-- Change Button -->
                                <button @click="changeColor(type)"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-indigo-500 transition">
                                    Change
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import VSwatches from 'vue-swatches';
import 'vue-swatches/dist/vue-swatches.css';

export default {
    components: { VSwatches },
    data() {
        return {
            noticeTypes: [],
            localFlashSuccess: '',
            localFlashError: '',
        };
    },
    methods: {
        async fetchNoticeTypes() {
            try {
                const response = await axios.get('/admin/notice-types');
                this.noticeTypes = response.data.noticeTypes.map(type => ({
                    ...type,
                    newColor: type.color || '#000000'
                }));
            } catch (error) {
                console.error('Error fetching notice types:', error);
            }
        },
        async changeColor(type) {
            try {
                const response = await axios.post(`/admin/notice-type/${type.id}/change-color`, {
                    color: type.newColor
                });
                this.localFlashSuccess = 'Notice type color updated successfully!';
                setTimeout(() => {
                    this.localFlashSuccess = '';
                }, 3000);
                await this.fetchNoticeTypes();
            } catch (error) {
                console.error('Error changing color:', error);
                this.localFlashError = 'An error occurred while changing the color.';
                setTimeout(() => {
                    this.localFlashError = '';
                }, 3000);
            }
        },
        updateColor(type, color) {
            type.newColor = color;
        }
    },
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

.bg-white {
    overflow: visible !important;
}

table {
    overflow: visible !important;
}

tbody {
    overflow: visible !important;
}
</style>

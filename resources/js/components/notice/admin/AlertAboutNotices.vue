<template>
    <div class="mb-6">
        <div class="w-full flex grid-cols-1 md:grid-cols-3 gap-4 bg-white rounded-lg p-4">
            <div v-if="upcomingNotices.length" class="w-full bg-blue-50 p-4 rounded-lg shadow-md">
                <h3 class="text-sm font-bold text-blue-700 mb-2 border-b-2 pb-2">Notices Going Live Soon</h3>
                <ul class="divide-y divide-blue-300">
                    <li v-for="notice in upcomingNotices" :key="notice.id" class="py-3">
                            <span class="whitespace-nowrap flex">
                            <p class="text-sm font-medium text-gray-900 mr-1">{{ notice.form.id }} -</p>
                            <p class="text-sm font-medium text-gray-900">{{ notice.form.name }}</p>
                            </span>
                        <p class="text-xs text-gray-600">
                            Scheduled At: {{ formatDate(notice.form.event_date) }}
                            <span class="italic">({{ notice.form.event_date | ago }})</span>
                        </p>
                    </li>
                </ul>
            </div>
            <div v-if="activeNotices.length" class="w-full bg-green-50 p-4 rounded-lg shadow-md">
                <h3 class="text-sm font-bold text-green-500 mb-2 border-b-2 pb-2">Currently Active Notices</h3>
                <ul class="divide-y divide-green-300">
                    <li v-for="notice in activeNotices" :key="notice.id" class="w-full py-3 flex">
                        <div>
                            <span class="whitespace-nowrap flex">
                            <p class="text-sm font-medium text-gray-900 mr-1">{{ notice.form.id }} -</p>
                            <p class="text-sm font-medium text-gray-900">{{ notice.form.name }}</p>
                            </span>
                            <p class="text-xs text-gray-600">
                                Active Since: {{ formatDate(notice.form.event_date) }}
                                <span class="italic">(Expires {{ notice.form.expiry_date | ago }})</span>
                            </p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-xs text-gray-600">
                                Views: {{ notice.form.views_count || 0 }}
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-if="expiringNotices.length" class="w-full bg-red-50 p-4 rounded-lg shadow-md">
                <h3 class="text-sm font-bold text-red-700 mb-2 border-b-2 pb-2">Notices Expiring Soon</h3>
                <ul class="divide-y divide-red-300">
                    <li v-for="notice in expiringNotices" :key="notice.id" class="w-full py-3 flex">
                        <div>
                            <span class="whitespace-nowrap flex">
                            <p class="text-sm font-medium text-gray-900 mr-1">{{ notice.form.id }} -</p>
                            <p class="text-sm font-medium text-gray-900">{{ notice.form.name }}</p>
                            </span>
                            <p class="text-xs text-gray-600">
                                Expiry Date: {{ formatDate(notice.form.expiry_date) }}
                                <span class="italic">({{ notice.form.expiry_date | ago }})</span>
                            </p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-xs text-gray-600">
                                Views: {{ notice.form.views_count || 0 }}
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>


<script>
import moment from "moment";

export default {
    props: {
        notices: {
            type: Array,
            required: true,
        },
    },
    computed: {
        activeNotices() {
            return this.notices.filter(({form}) => {
                return form.is_active;
            });
        },
        upcomingNotices() {
            const now = moment();
            return this.notices.filter(({form}) => {
                const eventDate = moment(form.event_date);
                const isWeekend = [6, 0].includes(eventDate.day()); // 6 = Saturday, 0 = Sunday
                return !form.is_active && eventDate.isAfter(now) && eventDate.diff(now, "hours") <= 24 && !isWeekend;
            });
        },
        expiringNotices() {
            const now = moment();
            return this.notices.filter(({form}) => {
                const expiryDate = form.expiry_date ? moment(form.expiry_date) : null;
                return form.is_active && expiryDate && expiryDate.isAfter(now) && expiryDate.diff(now, "hours") <= 24;
            });
        },
    },
    filters: {
        ago(date) {
            if (!date) return "No Expiry";
            const parsedDate = moment(date);
            return parsedDate.isValid() ? parsedDate.fromNow() : "Invalid Date";
        },
    },
    methods: {
        formatDate(date) {
            return moment(date).format("MMM D, YYYY [at] h:mm A");
        },
    },
};
</script>

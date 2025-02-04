<template>
    <div v-if="filteredNotices.length > 0" class="fixed inset-0 flex items-start justify-center p-4">
        <div v-for="notice in filteredNotices" :key="notice.id" class="notice-banner" :class="getNoticeClass(notice)">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center">
                    <div class="mr-4">
                        <svg v-if="notice.notification_type_id === 'announcement'" xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m-6 0h6m-3-3V8m0-4a9 9 0 110 18 9 9 0 010-18z" />
                        </svg>
                        <svg v-else-if="notice.notification_type_id === 'information'"
                            xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                        <svg v-else-if="notice.notification_type_id === 'outage'" xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m-6 0h6m-3-3V8m0-4a9 9 0 110 18 9 9 0 010-18z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold">{{ notice.name }}</p>
                        <p>{{ notice.description }}</p>
                    </div>
                </div>
                <button @click="dismissNotice(notice.id)" class="ml-4 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        notices: {
            type: Array,
            required: true,
        }
    },
    data() {
        return {
            dismissedNotices: this.getDismissedNotices(),
        };
    },
    computed: {
        filteredNotices() {
            return this.notices.filter(notice => !this.dismissedNotices.includes(notice.id.toString()));
        }
    },
    methods: {
        getNoticeClass(notice) {
            return {
                'announcement': 'bg-orange-100 border-l-4 border-orange-500 text-orange-700',
                'information': 'bg-blue-100 border-l-4 border-blue-500 text-blue-700',
                'outage': 'bg-red-100 border-l-4 border-red-500 text-red-700'
            }[notice.notification_type_id];
        },
        dismissNotice(noticeId) {
            // this.dismissedNotices.push(noticeId.toString());
            // this.saveDismissedNotices();
            this.$emit('dismiss', noticeId);
        },
        saveDismissedNotices() {
            document.cookie = `dismissed_notices=${JSON.stringify(this.dismissedNotices)}; path=/; max-age=86400`;
        },
        getDismissedNotices() {
            const cookie = document.cookie.split('; ').find(row => row.startsWith('dismissed_notices='));
            return cookie ? JSON.parse(cookie.split('=')[1]) : [];
        }
    }
};
</script>

<style>
.notice-banner {
    max-width: 600px;
    margin-bottom: 1rem;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.notice-banner-enter-active,
.notice-banner-leave-active {
    transition: opacity 0.5s, transform 0.5s;
}

.notice-banner-enter,
.notice-banner-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
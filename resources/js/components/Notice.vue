<template>
    <div class="relative w-full flex items-center">
        <div v-if="filteredNotices.length > 0" class="notice-container w-full">
            <div v-for="notice in filteredNotices" :key="notice.id"
                class="notice-banner flex items-center justify-between w-full p-1" :class="getNoticeClass(notice)">

                <div class="flex items-center">
                    <p class="font-semibold text-lg mb-1 p-1">{{ notice.name }}:</p>
                    <p class="text-sm ml-1">{{ notice.description }}</p>
                </div>

                <button @click="handleNotice(notice.id)" class="dismiss-btn hover">
                    &times;
                </button>
            </div>
        </div>
    </div>
</template>



<script>
import CNotice from '@/utilities/CNotice.js';

export default {
    data() {
        return {
            notices: [],
            dismissedNotices: this.getDismissedNotices(),
            isLoggedIn: window.Laravel.isLoggedIn,
            user: window.Laravel.user,
        };
    },
    computed: {
        filteredNotices() {
            return this.notices.filter(notice => {
                return !this.dismissedNotices.includes(notice.id.toString());
            });
        }
    },
    async created() {
        if (this.isLoggedIn) {
            document.cookie = "dismissed_notice=; expires=Thu, 01 Jan 1970 00:00:00; path=/";
            localStorage.removeItem('dismissedNotices');
            this.notices = await CNotice.unreadNotices();
        }
        else {
            this.notices = await CNotice.fetchNotices();
        }
    },
    methods: {
        handleNotice(noticeId) {
            if (this.isLoggedIn) {
                CNotice.acknowledge(noticeId);
                this.dismissNotice(noticeId);
            }
            else {
                this.dismissNotice(noticeId);
            }
        },
        getNoticeClass(notice) {
            return {
                '1': 'bg-orange-100 border-orange-500 text-orange-700',
                '2': 'bg-blue-100 border-blue-500 text-blue-700',
                '3': 'bg-red-100 border-red-500 text-red-700'
            }[notice.notification_type_id];
        },
        dismissNotice(noticeId) {
            this.dismissedNotices.push(noticeId.toString());
            document.cookie = `dismissed_notice=${JSON.stringify(this.dismissedNotices)}; path=/; max-age=86400`;
            this.saveDismissedNotices();
        },
        saveDismissedNotices() {
            localStorage.setItem('dismissedNotices', JSON.stringify(this.dismissedNotices));
        },
        getDismissedNotices() {
            return JSON.parse(localStorage.getItem('dismissedNotices')) || [];
        }
    }
};
</script>

<style>
.notice-container {
    margin-left: 1rem;
    width: 100vw;
    margin: auto;
}

.notice-banner {
    position: relative;
    width: 100%;
}

.dismiss-btn {
    margin-right: 0.5rem;
    font-size: 30px;
    transition: transform 0.3s ease;
}

.dismiss-btn:hover {
    transform: scale(1.1);
}
</style>
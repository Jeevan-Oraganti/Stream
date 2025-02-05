<template>
    <div v-if="filteredNotices.length > 0"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 flex flex-col items-center space-y-4 w-full max-w-lg">
        <div>
            <div v-for="notice in filteredNotices" :key="notice.id"
                class="notice-banner flex items-center justify-between w-full p-4 shadow-lg rounded-lg border-l-4"
                :class="getNoticeClass(notice)">

                <div class="items-center">
                    <p class="font-semibold text-lg mb-1">{{ notice.name }}</p>
                    <p class="text-sm">{{ notice.description }}</p>
                </div>

                <button @click="handleNotice(notice.id)" class="dismiss-btn hover">
                    &times;
                </button>
            </div>
        </div>
    </div>
</template>


<script>
import CNotices from '@/utilities/CNotices';

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
            this.notices = await CNotices.unreadNotices();
        }
        else {
            this.notices = await CNotices.fetchNotices();
        }
    },
    methods: {
        handleNotice(noticeId) {
            if (this.isLoggedIn) {
                CNotices.markAsRead(noticeId);
                this.dismissNotice(noticeId);
            }
            else {
                this.dismissNotice(noticeId);
            }
        },
        getNoticeClass(notice) {
            return {
                '1': 'bg-orange-100 border-l-4 border-orange-500 text-orange-700',
                '2': 'bg-blue-100 border-l-4 border-blue-500 text-blue-700',
                '3': 'bg-red-100 border-l-4 border-red-500 text-red-700'
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
.dismiss-btn {
    font-size: 30px;
    transition: transform 0.3s ease;
}

.dismiss-btn:hover {
    transform: scale(1.1);
}

.notice-banner {
    position: fixed;
    top: 1rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    max-width: 600px;
    margin-bottom: 1rem;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, opacity 0.3s ease;
    width: 100%;
}
</style>

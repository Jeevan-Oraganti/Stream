<template>
    <div class="relative top-0 left-0 w-full flex items-center justify-center">
    <div v-if="filteredNotices.length > 0" class="notice-container w-full">
        <div v-for="notice in filteredNotices" :key="notice.id"
             class="notice-banner flex items-center justify-between px-4 py-2 w-full text-white border-b-2"
             :style="{ borderColor: `${notice.notice_type.color}`,
                background: `linear-gradient(180deg, ${shadeColor(notice.notice_type.color, 80)} 0%, ${shadeColor(notice.notice_type.color, 40)} 100%)`
            }">

            <div class="flex items-center">
                <p class="font-semibold text-sm ml-1"
                   :style="{ color:`${shadeTextColor(notice.notice_type.color, 30)}` }">
                    {{ notice.name }}:
                </p>
                <p class="text-sm ml-1"
                   :style="{ color: `${shadeTextColor(notice.notice_type.color, 30)}` }">
                    {{ notice.description }}
                </p>
                <p class="text-sm ml-2"
                   :style="{ color: `${shadeTextColor(notice.notice_type.color, 30)}` }">
                    - {{ notice.created_at | ago }}
                </p>
            </div>

            <button @click="handleNotice(notice.id)" class="hover" :style="{ color: `${shadeTextColor(notice.notice_type.color, 30)}` }">

            <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
    </div>
</div>
</template>


<script>
import CNotice from "@/components/notice/CNotice.js";
import moment from 'moment-timezone';
import CNotices from "@/components/notice/CNotices.js";

moment.tz.setDefault("Asia/Kolkata");

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
            // Returns the first notice that has not been dismissed
            const notice = this.notices.find(notice =>
                !this.dismissedNotices.includes(notice.id.toString())
            );

            return notice ? [notice] : [];
        }
    },
    filters: {
        // Formats a date to a relative time (e.g., "2 hours ago")
        ago(date) {
            return moment(date).fromNow();
        },
    },
    async created() {
        // Fetches unread notices for the logged-in user or guest
        if (this.isLoggedIn) {
            document.cookie = "dismissed_notice=; expires=Thu, 01 Jan 1970 00:00:00; path=/";
            localStorage.removeItem('dismissedNotices');
            this.notices = await CNotices.unreadNoticesForUser();
        } else {
            this.notices = await CNotices.unreadNoticesForGuest();
        }
    },
    methods: {
        // Adjusts the shade of the text color based on the given percentage
        shadeTextColor(color, percent) {
            let num = parseInt(color.replace("#", ""), 16),
                amt = Math.round(2.55 * percent), // Adjust brightness
                R = (num >> 16) - amt, // Reduce Red
                G = ((num >> 8) & 0x00FF) - amt, // Reduce Green
                B = (num & 0x0000FF) - amt; // Reduce Blue

            return (
                "#" +
                (0x1000000 +
                    (R > 0 ? R : 0) * 0x10000 +
                    (G > 0 ? G : 0) * 0x100 +
                    (B > 0 ? B : 0))
                    .toString(16)
                    .slice(1)
            );
        },

        // Generates a lighter or darker shade of a given color
        shadeColor(color, percent) {
            let num = parseInt(color.replace("#", ""), 16),
                amt = Math.round(2.55 * percent),
                R = (num >> 16) + amt,
                G = ((num >> 8) & 0x00FF) + amt,
                B = (num & 0x0000FF) + amt;

            return (
                "#" +
                (0x1000000 +
                    (R < 255 ? (R < 1 ? 0 : R) : 255) * 0x10000 +
                    (G < 255 ? (G < 1 ? 0 : G) : 255) * 0x100 +
                    (B < 255 ? (B < 1 ? 0 : B) : 255))
                    .toString(16)
                    .slice(1)
            );
        },

        // Handles the dismissal of a notice
        handleNotice(noticeId) {
            if (this.isLoggedIn) {
                const cNotice = new CNotice(noticeId);
                cNotice.acknowledge();
                this.dismissNotice(noticeId);
            } else {
                this.dismissNotice(noticeId);
            }
        },

        // Dismisses a notice and updates the dismissed notices list
        dismissNotice(noticeId) {
            this.dismissedNotices.push(noticeId.toString());
            document.cookie = `dismissed_notice=${JSON.stringify(this.dismissedNotices)}; path=/; max-age=86400`;
            this.saveDismissedNotices();
        },

        // Saves the dismissed notices to localStorage
        saveDismissedNotices() {
            localStorage.setItem('dismissedNotices', JSON.stringify(this.dismissedNotices));
        },

        // Retrieves the dismissed notices from localStorage
        getDismissedNotices() {
            return JSON.parse(localStorage.getItem('dismissedNotices')) || [];
        }
    }
};
</script>

<style>
.notice-container {
    width: 100vw;
}

.notice-banner {
    position: relative;
    width: 100%;
}

/*.dismiss-btn {*/
/*    margin-right: 0.5rem;*/
/*    font-size: 30px;*/
/*    transition: transform 0.3s ease;*/
/*}*/

/*.dismiss-btn:hover {*/
/*    transform: scale(1.1);*/
/*}*/
</style>

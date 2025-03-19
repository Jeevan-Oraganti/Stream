<template>
    <div class="relative top-0 left-0 w-full flex items-center justify-center">
    <div v-if="filteredNotices.length > 0" class="notice-container w-full">
        <div v-for="notice in filteredNotices" :key="notice.id"
             class="notice-banner flex items-center justify-between w-full p-1 text-white"
             :style="{
                background: `linear-gradient(180deg, ${shadeColor(notice.notice_type.color, 80)} -50%, ${shadeColor(notice.notice_type.color, 40)} 100%)`
            }">

            <div class="flex items-center">
                <p class="font-semibold text-lg mb-1 p-1"
                   :style="{ color: notice.notice_type.color }">
                    {{ notice.name }}:
                </p>
                <p class="text-sm ml-1"
                   :style="{ color: notice.notice_type.color }">
                    {{ notice.description }}
                </p>
                <p class="text-sm ml-2"
                   :style="{ color: notice.notice_type.color }">
                    - {{ notice.created_at | ago }}
                </p>
            </div>

            <button @click="handleNotice(notice.id)" class="dismiss-btn hover text-white">
                &times;
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
            const notice = this.notices.find(notice =>
                !this.dismissedNotices.includes(notice.id.toString())
            );

            return notice ? [notice] : [];
        }
    },
    filters: {
        ago(date) {
            return moment(date).fromNow();
        },
    },
    async created() {
        if (this.isLoggedIn) {
            document.cookie = "dismissed_notice=; expires=Thu, 01 Jan 1970 00:00:00; path=/";
            localStorage.removeItem('dismissedNotices');
            this.notices = await CNotices.unreadNoticesForUser();
        } else {
            this.notices = await CNotices.unreadNoticesForGuest();
        }
    },
    methods: {
        // Function to generate lighter or darker shades
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

        handleNotice(noticeId) {
            if (this.isLoggedIn) {
                const cNotice = new CNotice(noticeId);
                cNotice.acknowledge();
                this.dismissNotice(noticeId);
            } else {
                this.dismissNotice(noticeId);
            }
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
    width: 100vw;
    margin: auto;
}

.notice-banner {
    margin-bottom: 1px;
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

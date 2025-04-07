<template>
    <div class="relative top-0 left-0 w-full flex items-center justify-center">
        <div v-if="visibleNotices.length > 0" class="notice-container w-full">
            <div v-for="notice in visibleNotices" :key="notice.id"
                 class="notice-banner flex items-center justify-between px-4 py-2 w-full text-white border-b-2" :style="{
                    borderColor: `${notice.notice_type.color}`,
                    background: `linear-gradient(180deg, ${getBackgroundShade(notice.notice_type.color, 80)} 0%, ${getBackgroundShade(notice.notice_type.color, 40)} 100%)`
                }">

                <div class="flex items-center">
                    <p class="font-semibold text-sm ml-1"
                       :style="{ color: `${getTextShade(notice.notice_type.color, 30)}` }">
                        {{ notice.name }}:
                    </p>
                    <p class="text-sm ml-2" :style="{ color: `${getTextShade(notice.notice_type.color, 30)}` }">
                        {{ notice.description }}
                    </p>
                    <p class="text-sm ml-2" :style="{ color: `${getTextShade(notice.notice_type.color, 30)}` }">
                        {{ formatRelativeDay(notice.expiry_date) }}
                    </p>

                </div>

                <button @click="handleNoticeDismissal(notice.id)" class="hover"
                        :style="{ color: `${getTextShade(notice.notice_type.color, 30)}` }">
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
import {shadeColor, shadeTextColor} from "@/components/notice/colorUtils.js";

moment.tz.setDefault("Asia/Kolkata");

export default {
    data() {
        return {
            notices: [],
            isLoggedIn: window.Laravel.isLoggedIn,
        };
    },
    computed: {
        //unreadNoticesForUser
        visibleNotices() {
            const dismissedNotices = CNotices.getDismissedNoticesList();
            const visibleNotices = this.notices.filter(notice => !dismissedNotices.includes(notice.id));
            return visibleNotices.length > 0 ? [visibleNotices[0]] : [];
        }
    },
    async created() {
        //fetch the notices
        try {
            this.notices = await CNotices.fetchUnreadNotices(this.isLoggedIn);
        } catch (error) {
            console.error("Error fetching notices:", error);
            this.notices = [];
        }
    },
    methods: {
        /**
         * Handles the dismissal of a notice when clicked on x.
         * If the user is logged in, it acknowledges the notice on the server.
         * Otherwise, it simply dismisses the notice locally storing the dismissed notice ID in the local storage.
         */
        async handleNoticeDismissal(noticeId) {
            const notice = new CNotice(noticeId);
            await notice.noticeDismissedByUser(this.isLoggedIn); // Acknowledge or dismiss the notice

            // Remove the dismissed notice from the notices array
            this.notices = this.notices.filter(n => n.id !== noticeId);
        },

        formatRelativeDay(date) {
            const today = moment().startOf('day');
            const noticeDate = moment(date).startOf('day');

            if (noticeDate.isSame(today, 'day')) {
                return ` - Today`;
            } else if (noticeDate.diff(today, 'days') === 1) {
                return ` - Tomorrow`;
            } else {
                return moment(date).format('MMMM Do, YYYY');
            }
        },
        getBackgroundShade(color, percent) {
            return shadeColor(color, percent);
        },
        getTextShade(color, percent) {
            return shadeTextColor(color, percent);
        },

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
</style>

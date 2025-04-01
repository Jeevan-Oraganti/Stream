<template>
    <div class="relative top-0 left-0 w-full flex items-center justify-center">
        <div v-if="filteredNotices.length > 0" class="notice-container w-full">
            <div v-for="notice in filteredNotices" :key="notice.id"
                class="notice-banner flex items-center justify-between px-4 py-2 w-full text-white border-b-2" :style="{
                    borderColor: `${notice.notice_type.color}`,
                    background: `linear-gradient(180deg, ${shadeColor(notice.notice_type.color, 80)} 0%, ${shadeColor(notice.notice_type.color, 40)} 100%)`
                }">

                <div class="flex items-center">
                    <p class="font-semibold text-sm ml-1"
                        :style="{ color: `${shadeTextColor(notice.notice_type.color, 30)}` }">
                        {{ notice.name }}:
                    </p>
                    <p class="text-sm ml-2" :style="{ color: `${shadeTextColor(notice.notice_type.color, 30)}` }">
                        {{ notice.description }}
                    </p>
                    <p class="text-sm ml-2" :style="{ color: `${shadeTextColor(notice.notice_type.color, 30)}` }">
                        {{ formatNoticeRange(notice.expiry_date, notice.scheduled_at, notice.notice_type_id) }}
                    </p>

                </div>

                <button @click="handleNotice(notice.id)" class="hover"
                    :style="{ color: `${shadeTextColor(notice.notice_type.color, 30)}` }">
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
            const notice = this.notices.find(notice =>
                !this.dismissedNotices.includes(notice.id.toString())
            );
            return notice ? [notice] : [];
        }
    },
    async created() {
        if (this.isLoggedIn) {
            document.cookie = "dismissed_notice=; expires=Thu, 01 Jan 1970 00:00:00; path=/";
            localStorage.removeItem('dismissedNotices');
        }
        this.notices = await CNotices.fetchUnreadNotices(this.isLoggedIn)
    },
    methods: {
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
        formatNoticeRange(expiryDate, scheduledAt, noticeTypeId) {
            const today = moment().startOf('day');
            const expiryMoment = moment(expiryDate).endOf('day'); // Ensure whole day is considered
            const scheduledMoment = moment(scheduledAt).clone().startOf('day'); // Start of event

            // If it's a holiday (notice_type_id === 4)
            if (noticeTypeId === 4) {
                if (!expiryMoment) {
                    return "Holiday notice (Ongoing until further update)";
                }

                let holidayStart = scheduledMoment.clone().add(1, 'day'); // Holiday starts the next day
                let totalHolidayDays = expiryMoment.diff(holidayStart, 'days') + 1;
                let remainingDays = expiryMoment.diff(today, 'days') + 1;

                // Before holiday starts
                if (today.isBefore(holidayStart)) {
                    const relativeDay = this.formatRelativeDay(holidayStart);
                    return `Holiday for ${totalHolidayDays} days (Starts ${relativeDay})`;
                }

                // If today falls within the holiday period
                if (today.isBetween(holidayStart, expiryMoment, 'day', '[]')) {
                    if (remainingDays === 1) {
                        return `Holiday - Ends today`; // Last day of the holiday
                    } else {
                        return `Holiday - ${remainingDays} days remaining`; // Ongoing holiday with days left
                    }
                }

                return ""; // Past holiday, no need to display
            }

            // If scheduled_at is given, assume event starts the next day
            if (scheduledAt) {
                scheduledMoment.add(1, 'day'); // Event starts after scheduling
            }

            // If the notice is only for one day
            if (scheduledMoment.isSame(expiryMoment, 'day')) {
                return `${this.formatRelativeDay(expiryDate)}`;
            }

            // Prevent invalid date issues
            if (scheduledMoment.isAfter(expiryMoment)) {
                return `on ${expiryMoment.format('MMMM D, YYYY')}`;
            }

            // If the event hasn't started yet
            if (today.isBefore(scheduledMoment)) {
                return `from ${scheduledMoment.format('MMMM D, YYYY')} to ${expiryMoment.format('MMMM D, YYYY')}`;
            }

            // If the event is ongoing
            if (today.isBetween(scheduledMoment, expiryMoment, 'day', '[]')) {
                if (today.isSame(expiryMoment, 'day')) {
                    return `today (Ends today)`;
                }
                return `today (From ${scheduledMoment.format('MMMM D')} to ${expiryMoment.format('MMMM D, YYYY')})`;
            }

            // If the event has already ended, return an empty string (or past notice handling)
            return "";
        },
        formatHolidayRange(expiryDate, scheduledAt, message) {
            const today = moment().startOf('day');
            const expiryMoment = moment(expiryDate).endOf('day');
            const scheduledMoment = moment(scheduledAt).clone().add(1, 'day').startOf('day'); // First day of holiday

            // If it's a single-day holiday, return "Today" or specific date
            if (scheduledMoment.isSame(expiryMoment, 'day')) {
                return this.formatRelativeDay(expiryDate, message);
            }

            // Prevent invalid data where scheduled > expiry
            if (scheduledMoment.isAfter(expiryMoment)) {
                return `on ${expiryMoment.format('MMMM D, YYYY')}`;
            }

            // If today is before the holiday starts
            if (today.isBefore(scheduledMoment)) {
                return `from ${scheduledMoment.format('MMMM D, YYYY')} to ${expiryMoment.format('MMMM D, YYYY')}`;
            }

            // If today is within the holiday range
            if (today.isBetween(scheduledMoment, expiryMoment, 'day', '[]')) {
                if (today.isSame(expiryMoment, 'day')) {
                    return `today (ends today)`;
                }
                return `today (from ${scheduledMoment.format('MMMM D')} to ${expiryMoment.format('MMMM D, YYYY')})`;
            }

            // If the holiday has already ended, return empty string
            return "";
        },


        shadeTextColor(color, percent) {
            let num = parseInt(color.replace("#", ""), 16),
                amt = Math.round(2.55 * percent),
                R = (num >> 16) - amt,
                G = ((num >> 8) & 0x00FF) - amt,
                B = (num & 0x0000FF) - amt;

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
}

.notice-banner {
    position: relative;
    width: 100%;
}
</style>

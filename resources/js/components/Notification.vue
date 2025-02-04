<template>
    <div v-if="notice" class="notice-banner" :class="getNoticeClass(notice)">
        <p>{{ notice.description }}</p>
        <button @click="dismissNotice(notice.id)">&times;</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            notice: null
        };
    },
    created() {
        this.fetchNotice();
    },
    methods: {
        async fetchNotice() {
            const dismissedNotice = localStorage.getItem('dismissedNotice');
            const response = await fetch('/api/notice');
            let data = await response.json();
            if (data && data.id !== parseInt(dismissedNotice)) {
                this.notice = data;
            }
        },
        dismissNotice(id) {
            localStorage.setItem('dismissedNotice', id);
            this.notice = null;
        },
        getNoticeClass(notice) {
            return {
                'announcement': 'bg-orange-500',
                'information': 'bg-blue-500',
                'outage': 'bg-red-500'
            }[notice.notification_type_id];
        }
    }
};
</script>
<style>
.notice-banner {
    position:fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    padding: 10px;
    color: white;
    display: flex;
    justify-content: space-between;
}
</style>

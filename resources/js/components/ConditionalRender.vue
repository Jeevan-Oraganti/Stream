<template>
    <transition name="fade">
        <div v-show="isVisible">
            <slot></slot>
        </div>
    </transition>
</template>

<script>
import inView from "in-viewport";

export default {
    props: ['whenHidden'],
    data() {
        return {
            isVisible: false
        };
    },
    mounted() {
        window.addEventListener('scroll', this.checkDisplay, { passive: true });
        this.checkDisplay();
    },
    beforeDestroy() {
        window.removeEventListener('scroll', this.checkDisplay);
    },
    methods: {
        checkDisplay() {
            const targetElement = document.querySelector(this.whenHidden);
            if (targetElement) {
                const isInViewport = inView(targetElement);
                this.isVisible = !isInViewport;
            }
        }
    }
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>

<template>
    <div class="dropdown inline">
        <div class="dropdown-trigger absolute -top-8 left-3 text-white mt-4" @click="isOpen = !isOpen">
            <slot name="trigger"></slot>
        </div>

        <transition name="pop-out-quick">
            <div v-show="isOpen"
                 class="absolute bg-gray-900 mt-2 py-2 rounded shadow text-white z-10"
                 @click="isOpen = !isOpen">
                <slot></slot>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isOpen: false
        };
    },

    watch: {
        isOpen() {
            if (this.isOpen) {
                document.addEventListener('click', this.closeDropdown)
            }
        }
    },

    methods: {
        closeDropdown(event) {
            if (!event.target.closest('.dropdown')) {
                this.isOpen = false;
            }
        }
    }
}
</script>

<style>
.pop-out-quick-enter,
.pop-out-quick-leave-active {
    transition: all 0.4s;
}

.pop-out-quick-enter,
.pop-out-quick-leave-active {
    opacity: 0;
    transform: translateY(-7px);
}
</style>

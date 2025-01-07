<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>
import {throttle} from "lodash";

export default {
    mounted() {
        const el = this.$el;

        if (el) {
            const originalOffsetTop = el.offsetTop;

            window.addEventListener("scroll", throttle(function () {
                el.classList[
                    window.scrollY >= originalOffsetTop ? 'add' : 'remove'
                    ]("fixed", "top-0", "z-10", "bg-white", "w-full", "p-1");
            }));
        }
    },
}
</script>

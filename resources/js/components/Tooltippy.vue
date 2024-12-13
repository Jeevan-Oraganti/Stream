<template>
    <div v-show="false">
        <slot></slot>
    </div>
</template>

<script>
import Tippy from "tippy.js";

export default {
    props: {
        name: {},
        placement: {default: 'top'}
    },

    mounted() {
        document
            .querySelectorAll(`[data-tooltip-name=${this.name}]`)
            .forEach((elem) => {
                new Tippy(elem, {
                    placement: this.placement,
                    content: this.$el.innerHTML,
                    allowHTML: true
                });
            });
    }
};

</script>

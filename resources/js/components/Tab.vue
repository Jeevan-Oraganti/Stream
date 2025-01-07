<template>

    <div role="tabpanel" v-show="show">
        <slot></slot>
    </div>

</template>

<script>
export default {
    props: ['title'],

    data() {
        return {
            show: false
        };
    },
    methods: {
        selectTab() {
            this.$emit('tab-selected', this.title);
        }
    }
}
</script>

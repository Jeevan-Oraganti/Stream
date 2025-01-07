<template>
    <div>
        <slot></slot>
    </div>
</template>
<script>
import Flickity from 'flickity';
import 'flickity/dist/flickity.min.css';

export default {
    props: {
        wraparound: {type: Boolean, default: true},
        autoplay: {type: Boolean, default: false}
    },
    mounted() {
        new Flickity(this.$el, {
            wrapAround: this.wraparound,
            autoPlay: this.autoplay,
            cellAlign: 'center',
            contain: true,
            draggable: true,
            pageDots: true
        });
    }
}
</script>

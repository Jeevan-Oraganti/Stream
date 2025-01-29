<template>
    <div>
        <div class="loading-bar" :style="{ width: progress + '%' }" :class="{ complete: progress === 100 }"></div>
    </div>
</template>

<script>
export default {
    props: {
        progress: {
            type: Number,
            required: true,
        }
    }
}
</script>

<style scoped>
.loading-bar {
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    width: 0;
    background: rgb(57, 166, 255);
    background: linear-gradient(90deg, rgba(50, 137, 240, 1) 0%, rgba(50, 137, 240, 1) 70%, rgba(37, 197, 239, 1) 100%);
    transition: width 0.3s ease, opacity 0.3s ease;
    z-index: 11;
    opacity: 1;
}

.loading-bar.complete {
    box-shadow: 0 0 10px rgba(50, 137, 240, 1), 0 0 10px rgba(37, 197, 239, 1);
    opacity: 0;
    transition: opacity 0.5s ease;
}
</style>

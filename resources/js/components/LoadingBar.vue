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
    position: relative;
    border-radius: 10px;
    top: 0;
    left: 0;
    height: 4px;
    width: 0;
    background: linear-gradient(90deg, rgba(0, 123, 255, 1) 0%, rgba(0, 86, 179, 1) 80%, rgba(0, 217, 255, 1) 100%);
    transition: width 0.3s ease, opacity 0.3s ease;
    z-index: 11;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    filter: brightness(1.2);
}

.loading-bar.complete {
    box-shadow: 0 0 10px rgba(50, 137, 240, 1), 0 0 10px rgba(37, 197, 239, 1);
    opacity: 0;
    transition: opacity 0.5s ease;
}
</style>

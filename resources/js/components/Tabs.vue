<template>

    <div>
        <ul class="flex mb-4 border-b border-gray-400 justify-around" role="tablist">
            <li v-for="(tab, index) in tabs"
                class="px-4 py-2 cursor-pointer"
                :class="{'border border-b-0 rounded-t-lg bg-white': tab === activeTab, 'hover:bg-gray-200 hover:rounded-t-lg': tab !== activeTab}"
                :style="tab === activeTab ? 'margin-bottom: -1px': ''"
            >

                <button v-text="tab.title"
                        :class="{ 'text-black': tab === activeTab }"
                        class="focus:outline-none"
                        role="tab"
                        :aria-selected="tab.isActive"
                        @click="activeTab = tab"
                ></button>
            </li>
        </ul>

        <slot></slot>

    </div>

</template>

<script>
export default {
    data() {
        return {
            tabs: [],
            activeTab: null
        };
    },

    mounted() {
        this.tabs = this.$children;
        this.activeTab = this.tabs[0]
    },

    watch: {
        activeTab(activeTab) {
            this.tabs.map(tab => tab.show = tab === activeTab)
        }
    }
}
</script>

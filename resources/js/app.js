import "./bootstrap";
import router from "./routes";
import Vue from "vue";
import Carousel from "./components/Carousel.vue";
import SeriesDropdown from "./components/dropdown/SeriesDropdown.vue";
import CatalogDropdown from "./components/dropdown/CatalogDropdown.vue";
import PodcastsDropdown from "./components/dropdown/PodcastsDropdown.vue";
import SupportButton from "./components/SupportButton.vue";
import ContactButton from "./components/ContactButton.vue";
import ServicesButton from "./components/ServicesButton.vue";
import AboutUsButton from "./components/AboutUsButton.vue";
import Accordian from "./components/Accordian.vue";
import Tippy from "tippy.js";
import Tooltippy from "./components/Tooltippy.vue";
import PinnedToTop from "./components/PinnedToTop.vue";
import 'tippy.js/dist/tippy.css';


Vue.component("Carousel", Carousel);
Vue.component("SeriesDropdown", SeriesDropdown);
Vue.component("CatalogDropdown", CatalogDropdown);
Vue.component("PodcastsDropdown", PodcastsDropdown);
Vue.component("SupportButton", SupportButton);
Vue.component("ContactButton", ContactButton);
Vue.component("ServicesButton", ServicesButton);
Vue.component("AboutUsButton", AboutUsButton);
Vue.component("Accordion", Accordian);
Vue.component("PinnedToTop", PinnedToTop);
Vue.component("Tooltippy", Tooltippy);


Vue.directive('tooltip', {
    bind(elem, binding) {

        Tippy(elem, {
            content: binding.value,
            placement: binding.arg,
        })
    }
});

new Vue({
    el: "#app",
    router,
});

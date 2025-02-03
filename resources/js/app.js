import "./bootstrap";
import router from "./routes";
import Vue from "vue";
import Welcome from "@/Pages/Welcome.vue";
import Carousel from "./components/Carousel.vue";
import SeriesDropdown from "./components/dropdown/SeriesDropdown.vue";
import CatalogDropdown from "./components/dropdown/CatalogDropdown.vue";
import PodcastsDropdown from "./components/dropdown/PodcastsDropdown.vue";
import SupportButton from "./components/footer/SupportButton.vue";
import ContactButton from "./components/footer/ContactButton.vue";
import ServicesButton from "./components/footer/ServicesButton.vue";
import AboutUsButton from "./components/footer/AboutUsButton.vue";
import Count from "./components/Count.vue";
import ConditionalRender from "./components/ConditionalRender.vue";
import Dropdown from "./components/Dropdown.vue";
import Tabs from "./components/Tabs.vue";
import Tab from "./components/Tab.vue";
import FAQ from "./views/FAQ.vue";
import Tooltippy from "./components/Tooltippy.vue";
import PinnedToTop from "./components/PinnedToTop.vue";
import "tippy.js/dist/tippy.css";
import AnalyticsDashboard from "./components/AnalyticsDashboard.vue";
import SearchBar from "./components/SearchBar.vue";
import LoadingBar from "./components/LoadingBar.vue";
import LoginForm from "./views/session/LoginForm.vue";
import RegisterForm from "./views/session/RegisterForm.vue";

Vue.component("Welcome", Welcome);
Vue.component("Carousel", Carousel);
Vue.component("SeriesDropdown", SeriesDropdown);
Vue.component("CatalogDropdown", CatalogDropdown);
Vue.component("PodcastsDropdown", PodcastsDropdown);
Vue.component("SupportButton", SupportButton);
Vue.component("ContactButton", ContactButton);
Vue.component("ServicesButton", ServicesButton);
Vue.component("AboutUsButton", AboutUsButton);
Vue.component("ConditionalRender", ConditionalRender);
Vue.component("Dropdown", Dropdown);
Vue.component("Count", Count);
Vue.component("FAQ", FAQ);
Vue.component("PinnedToTop", PinnedToTop);
Vue.component("Tooltippy", Tooltippy);
Vue.component("Tabs", Tabs);
Vue.component("Tab", Tab);
Vue.component("AnalyticsDashboard", AnalyticsDashboard);
Vue.component("SearchBar", SearchBar);
Vue.component("LoadingBar", LoadingBar);
Vue.component("LoginForm", LoginForm);
Vue.component("RegisterForm", RegisterForm);

new Vue({
    el: "#app",
    router,
});

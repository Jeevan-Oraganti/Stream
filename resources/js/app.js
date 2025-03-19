import "./bootstrap";
import Vue from "vue";
import VueJSModal from "vue-js-modal";
import router from "./routes";

import "@fortawesome/fontawesome-free/css/all.css";
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
import NoticeHeader from "./components/notice/NoticeHeader.vue";
import Dashboard from "@/components/admin/Dashboard.vue";
import SearchBar from "./components/SearchBar.vue";
import LoadingBar from "./components/LoadingBar.vue";
import LoginForm from "./views/session/LoginForm.vue";
import RegisterForm from "./views/session/RegisterForm.vue";
import Pagination from "@/components/Pagination.vue";
import noticeForm from "@/components/notice/admin/NoticeForm.vue";
import noticesList from "@/components/notice/admin/NoticesList.vue";
import colorTypes from "@/components/notice/admin/ColorTypes.vue";
import axios from "axios";

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.Vue = Vue;
Vue.use(VueJSModal);
Vue.component("colorTypes", colorTypes);
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
Vue.component("NoticeHeader", NoticeHeader);
Vue.component("Dashboard", Dashboard);
Vue.component("SearchBar", SearchBar);
Vue.component("LoadingBar", LoadingBar);
Vue.component("LoginForm", LoginForm);
Vue.component("RegisterForm", RegisterForm);
Vue.component("noticesList", noticesList);
Vue.component("Pagination", Pagination);
Vue.component("noticeForm", noticeForm);

new Vue({
    el: "#app",
    router,
});

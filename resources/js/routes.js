import VueRouter from "vue-router";

import Home from "./views/Home.vue";
import About from "./views/About.vue";
import Contact from "./views/Contact.vue";
import FAQ from "./views/FAQ.vue";
import NoticeHeader from "@/components/notice/NoticeHeader.vue";
import Tabs from "./components/Tabs.vue";
import Dashboard from "@/components/admin/Dashboard.vue";

let routes = [
    {
        path: "/",
        component: Home,
    },
    {
        path: "/about",
        component: About,
    },
    {
        path: "/faq",
        component: FAQ,
    },
    {
        path: "/contact",
        component: Contact,
    },
    {
        path: "/dashboard",
        component: Dashboard,
    },
    {
        path: "/tabs",
        component: Tabs,
    },
    {
        path: "/notice",
        component: NoticeHeader,
    },
];
export default new VueRouter({
    routes,
    linkActiveClass: "is-active",
});

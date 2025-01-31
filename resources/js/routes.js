import VueRouter from "vue-router";

import Home from "./views/Home.vue";
import About from "./views/About.vue";
import Contact from "./views/Contact.vue";
import FAQ from "./views/FAQ.vue";
import AnalyticsDashboard from "./components/AnalyticsDashboard.vue";
import Tabs from "./components/Tabs.vue";

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
        path: "/analytics",
        component: AnalyticsDashboard,
    },
    {
        path: "/tabs",
        component: Tabs,
    },
];
export default new VueRouter({
    routes,
    linkActiveClass: "is-active",
});

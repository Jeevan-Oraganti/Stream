import VueRouter from "vue-router";

import Home from "./views/Home.vue";
import About from "./views/About.vue";
import Contact from "./views/Contact.vue";
import FAQ from "./views/FAQ.vue";


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
];
export default new VueRouter({
    routes,
    linkActiveClass: "is-active",
});

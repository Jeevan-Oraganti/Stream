import VueRouter from "vue-router";

import Home from "./views/Home.vue";
import About from "./views/About.vue";
import Contact from "./views/Contact.vue";
import Accordian from "./views/Accordian.vue";


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
        component: Accordian,
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

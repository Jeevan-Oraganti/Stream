import VueRouter from "vue-router";
import Vue from "vue";
import axios from "axios";
import Form from "./utilities/Form";
import PortalVue from "portal-vue";
import VModal from "vue-js-modal";
import inView from "in-viewport";
import { Chart } from "chart.js";
import moment from "moment";

window.Vue = Vue;
//Vue.use(VueRouter);
Vue.use(PortalVue);
Vue.use(VModal);
Vue.use(inView);

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

window.Form = Form;

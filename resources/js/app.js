import Vue from 'vue/dist/vue.common.js';
import {BootstrapVue, BootstrapVueIcons, FormCheckboxPlugin} from "bootstrap-vue";
import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.min.css';

require('./bootstrap');
window.Vue = require('vue/dist/vue.common.js');
Vue.use(BootstrapVue);
Vue.use(BootstrapVueIcons);
Vue.use(FormCheckboxPlugin);
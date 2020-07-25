/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import router from "./routes";
import App from "./components/App";
import { Message, MessageBox } from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

Vue.use(VueRouter);
Vue.prototype.$message = Message;
Vue.prototype.$messageBox = MessageBox;
Vue.component('app', App)

new Vue({
    el: '#app',
    router
});



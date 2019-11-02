import Vue from 'vue';
import App from './modules/layout';
import store from './store';
import { mapActions } from 'vuex';
import VModal from 'vue-js-modal'
import Noty from 'noty';

import "noty/src/noty.scss";
import "noty/src/themes/sunset.scss";
import VueRouter from 'vue-router';
import '../sass/index.scss';
import TasksRoute from './modules/tasks';

Noty.overrideDefaults({
    layout: 'topRight',
    theme: 'sunset',
    timeout: 2000,
});

Vue.use(VModal, { dialog: true, dynamic: true, injectModalsContainer: true });
Vue.use(VueRouter);

const routes = [
    { path: '/tasks', component: TasksRoute },
    { path: '/tasks/:page', component: TasksRoute },
    { path: '*', component: TasksRoute },
];

const router = new VueRouter({
    routes,
});

const el = document.querySelector("#app");

new Vue({
    el,
    store,
    router,
    methods: {
        ...mapActions([
            'init'
        ]),
    },
    created() {

        let params = el.dataset;

        this.init({
            'api-link': params.apiLink,
        });
    },
    render: h => h(App),
});

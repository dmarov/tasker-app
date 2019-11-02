import Vue from 'vue';
import App from './modules/layout';
import store from './store';
import { mapActions } from 'vuex';
import VModal from 'vue-js-modal'
import VueRouter from 'vue-router';
import Noty from 'noty';

import CategoriesRoute from './modules/categories';
import CategoryProductsRoute from './modules/category-products';
import ProductsRoute from './modules/products';

import "noty/src/noty.scss";
import "noty/src/themes/sunset.scss";
import '../sass/index.scss';

Noty.overrideDefaults({
    layout: 'topRight',
    theme: 'sunset',
    timeout: 2000,
});

Vue.use(VModal, { dialog: true, dynamic: true, injectModalsContainer: true });
Vue.use(VueRouter);

const el = document.querySelector("#app");

const routes = [
    { path: '/categories', component: CategoriesRoute },
    { path: '/categories/:page', component: CategoriesRoute },
    { path: '/categories/:id/products', component: CategoryProductsRoute },
    { path: '/categories/:id/products/:page', component: CategoryProductsRoute },
    { path: '/products', component: ProductsRoute },
    { path: '/products/:page', component: ProductsRoute },
    { path: '*', component: CategoriesRoute },
];

const router = new VueRouter({
    routes,
});

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
            'products-link': params.productsLink,
            'categories-link': params.categoriesLink,
        });
    },
    render: h => h(App),
});

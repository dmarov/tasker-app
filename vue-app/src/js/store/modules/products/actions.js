import authfetch from '../../../lib/authfetch';
import Noty from 'noty';

export default {

    async setProductsPage(context, page) {

        let link = context.rootGetters.getParam('products-link');
        let url = new URL(link, location.origin);
        url.searchParams.set('page', page - 1);
        url.searchParams.set('sort', context.getters.getSorting());

        try {

            let response = await authfetch(url);
            let result = await response.json();
            context.commit('setProducts', result);

        } catch(e) {

            if (e.name == "AuthError") {

                new Noty({
                    text: e.message,
                    type: "error",
                }).show();

            } else throw e;
        }
    },
    async patchProduct(context, { id, patch }) {

        let product = context.getters.getProduct(id);
        let link = product._links.self.href;

        let options = {
            method: 'PATCH',
            headers: new Headers({
                "content-type": "application/json-patch+json",
            }),
            body: JSON.stringify(patch),
        };

        try {

            let response = await authfetch(link, options);

            if (response.ok) {
                context.commit('patchProduct', { id, patch });
            } else {

                new Noty({
                    text: "unable to patch product",
                    type: "error",
                }).show();
            }

        } catch(e) {

            if (e.name == "AuthError") {

                new Noty({
                    text: e.message,
                    type: "error",
                }).show();

            } else throw e;
        }
    },
    async refreshProducts(context) {

        let products = context.getters.getProducts();
        let page = products.page.number;
        context.dispatch('setProductsPage', page + 1);
    },
    async deleteProduct(context, id) {

        let product = context.getters.getProduct(id);
        let link = product._links.self.href;

        let options = {
            method: 'DELETE',
        };

        try {

            let response = await authfetch(link, options);

            if (response.ok)
                context.dispatch('refreshProducts');
            else {

                new Noty({
                    text: 'unable to delete product',
                    type: "error",
                }).show();
            }

        } catch(e) {

            if (e.name == "AuthError") {

                new Noty({
                    text: e.message,
                    type: "error",
                }).show();

            } else throw e;
        }
    },
    setSorting(context, value) {

        context.commit('setSorting', value);
    },
};

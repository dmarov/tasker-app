import authfetch from '../../../lib/authfetch';
import Noty from 'noty';

export default {

    async setProductsPage(context, { cid, page }) {

        let link = context.rootGetters.getParam('products-link');
        let url = new URL(link, location.origin);
        url.searchParams.set('categoryId', cid);
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
    async refreshProducts(context, { cid }) {

        let products = context.getters.getProducts();
        let page = products.page.number;
        context.dispatch('setProductsPage', { cid, page: page + 1 });
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
                context.dispatch('refreshProducts', { cid: product.categoryId });
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
    async appendProduct(context, { categoryId, title, description, price, count, imageUrl }) {

        let link = context.rootGetters.getParam('products-link');
        let url = new URL(link, location.origin);

        let options = {
            method: 'POST',
            headers: new Headers({
                "content-type": "application/json",
            }),
            body: JSON.stringify({
                categoryId,
                title,
                description,
                price,
                count,
                imageUrl,
            }),
        };

        let response = await authfetch(url, options);
        let result = await response.json();

        if (!response.ok) {

            new Noty({
                text: "unable to append product to category",
                type: "error",
            }).show();
        } else {

            context.dispatch('refreshProducts', { cid: categoryId });
            new Noty({
                text: "product created",
                type: "success",
            }).show();
        }
    },
    setSorting(context, value) {

        context.commit('setSorting', value);
    },
};

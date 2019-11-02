import authfetch from '../../../lib/authfetch';
import Noty from 'noty';

export default {

    async setCategoriesPage(context, page) {

        let link = context.rootGetters.getParam('categories-link');
        let url = new URL(link, location.origin);
        url.searchParams.set('page', page - 1);
        url.searchParams.set('sort', context.getters.getSorting());

        try {

            let response = await authfetch(url);
            let result = await response.json();
            context.commit('setCategories', result);

        } catch(e) {

            if (e.name == "AuthError") {

                new Noty({
                    text: e.message,
                    type: "error",
                }).show();

            } else throw e;
        }
    },
    async patchCategory(context, { id, patch }) {

        let category = context.getters.getCategory(id);
        let link = category._links.self.href;

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
                context.commit('patchCategory', { id, patch });
            } else {

                new Noty({
                    text: "unable to patch category",
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
    async refreshCategories(context) {

        let categories = context.getters.getCategories();
        let page = categories.page.number;
        context.dispatch('setCategoriesPage', page + 1);
    },
    async deleteCategory(context, id) {

        let category = context.getters.getCategory(id);
        let link = category._links.self.href;

        let options = {
            method: 'DELETE',
        };

        try {

            let response = await authfetch(link, options);

            if (response.ok)
                context.dispatch('refreshCategories');
            else {
            
                new Noty({
                    text: 'unable to delete category',
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
    async appendCategory(context, { title, description }) {

        let link = context.rootGetters.getParam('categories-link');
        let url = new URL(link, location.origin);

        let options = {
            method: 'POST',
            headers: new Headers({
                "content-type": "application/json",
            }),
            body: JSON.stringify({
                title,
                description,
            }),
        };

        let response = await authfetch(url, options);
        let result = await response.json();

        if (!response.ok) {

            new Noty({
                text: "unable to append category",
                type: "error",
            }).show();
        } else {
            context.dispatch('refreshCategories');
            new Noty({
                text: "category created",
                type: "success",
            }).show();
        }
    },
    setSorting(context, value) {

        context.commit('setSorting', value);
    },
};

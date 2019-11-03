import authfetch from '../../../lib/authfetch';
import Noty from 'noty';

export default {

    async setTasksPage(context, page) {

        let link = context.rootGetters.getParam('api-link');
        let url = new URL(link, location.origin);
        let limit = 3;
        url.searchParams.set('offset', (page - 1) * limit );
        url.searchParams.set('limit', limit );
        url.searchParams.set('sort', context.getters.getSorting());

        try {

            let response = await fetch(url);
            let result = await response.json();
            context.commit('setTasks', result);

        } catch(e) {

            if (e.name == "AuthError") {

                new Noty({
                    text: e.message,
                    type: "error",
                }).show();

            } else throw e;
        }
    },
    async patchTask(context, { id, patch }) {

        let task = context.getters.getTask(id);
        let link = task._links.self.href;

        let options = {
            method: 'POST',
            headers: new Headers({
                "content-type": "application/json-patch+json",
            }),
            body: JSON.stringify(patch),
        };

        try {

            let response = await authfetch(link, options);

            if (response.ok) {
                let result = await response.json();
                context.commit('setTask', { id, data: result });
            } else {

                let result = await response.json();
                new Noty({
                    text: result.errors[0].message,
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
    async refreshTasks(context) {

        let tasks = context.getters.getTasks();
        let page = Math.floor(tasks.offset / tasks.limit);
        context.dispatch('setTasksPage', page + 1);
    },
    async appendTask(context, fields) {

        let link = context.rootGetters.getParam('api-link');
        let url = new URL(link, location.origin);

        let options = {
            method: 'POST',
            headers: new Headers({
                "content-type": "application/json",
            }),
            body: JSON.stringify(fields),
        };

        let response = await fetch(url, options);
        let result = await response.json();

        if (!response.ok) {

            new Noty({
                text: result.errors[0].message,
                type: "error",
            }).show();
        } else {
            context.dispatch('refreshTasks');
            new Noty({
                text: "task created",
                type: "success",
            }).show();
        }
    },
    setSorting(context, value) {

        context.commit('setSorting', value);
    },
};

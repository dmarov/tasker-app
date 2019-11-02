import authfetch from '../../../lib/authfetch';
import Noty from 'noty';

export default {

    async setTasksPage(context, page) {

        let link = context.rootGetters.getParam('api-link');
        let url = new URL(link, location.origin);
        url.searchParams.set('page', page - 1);
        url.searchParams.set('sort', context.getters.getSorting());

        try {

            let response = await authfetch(url);
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
            method: 'PATCH',
            headers: new Headers({
                "content-type": "application/json-patch+json",
            }),
            body: JSON.stringify(patch),
        };

        try {

            let response = await authfetch(link, options);

            if (response.ok) {
                context.commit('patchTask', { id, patch });
            } else {

                new Noty({
                    text: "unable to patch task",
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
        let page = tasks.page.number;
        context.dispatch('setTasksPage', page + 1);
    },
    async deleteTask(context, id) {

        let task = context.getters.getTask(id);
        let link = task._links.self.href;

        let options = {
            method: 'DELETE',
        };

        try {

            let response = await authfetch(link, options);

            if (response.ok)
                context.dispatch('refreshTasks');
            else {
            
                new Noty({
                    text: 'unable to delete task',
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
    async appendTask(context, { title, description }) {

        let link = context.rootGetters.getParam('api-link');
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
                text: "unable to append task",
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

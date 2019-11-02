import jsonpatch from 'json-patch';
import Vue from 'vue';

export default {

    setTasks(state, result) {

        state.tasks = result;
    },
    patchTask(state, { id, patch }) {

        let items = state.tasks._embedded.items;
        let index = items.findIndex(item => item.id === id)
        let task = items[index];
        jsonpatch.apply(task, patch);
        Vue.set(items, index, item);
    },
    setTask(state, { id, data }) {

        let items = state.tasks._embedded.items;
        let index = items.findIndex(item => item.id === id)
        Vue.set(items, index, data);
    },
    setSorting(state, value) {

        state.sort = value;
    },
};

import jsonpatch from 'json-patch';

export default {

    setTasks(state, result) {

        state.tasks = result;
    },
    patchTask(state, { id, patch }) {

        let items = state.tasks._embedded.items;
        let task = items.find(item => item.id === id);
        jsonpatch.apply(task, patch);
        items[id] = task;
    },
    setSorting(state, value) {

        state.sort = value;
    },
};

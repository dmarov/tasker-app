export default {

    getTasks(state, getters) {

        return _ => state.tasks;
    },
    getTask(state, getters) {

        return id => {

            try {

                return getters.getTasks()._embedded.items.find(item => item.id === id);

            } catch (e) {}
        }
    },
    getSorting(state, getters) {
        return _ => state.sort;
    },
};

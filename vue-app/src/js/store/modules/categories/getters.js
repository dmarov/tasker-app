export default {

    getCategories(state, getters) {

        return _ => state.categories;
    },
    getCategory(state, getters) {

        return id => {

            try {

                return getters.getCategories()._embedded.items.find(item => item.id === id);

            } catch (e) {}
        }
    },
    getSorting(state, getters) {
        return _ => state.sort;
    },
};

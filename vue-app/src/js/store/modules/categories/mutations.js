import jsonpatch from 'json-patch';

export default {

    setCategories(state, result) {

        state.categories = result;
    },
    patchCategory(state, { id, patch }) {

        let items = state.categories._embedded.items;
        let category = items.find(item => item.id === id);
        jsonpatch.apply(category, patch);
        items[id] = category;
    },
    setSorting(state, value) {

        state.sort = value;
    },
};

import jsonpatch from 'json-patch';

export default {

    setProducts(state, result) {

        state.products = result;
    },
    patchProduct(state, { id, patch }) {

        let items = state.products._embedded.items;
        let product = items.find(item => item.id === id);
        jsonpatch.apply(product, patch);
        items[id] = product;
    },
    setSorting(state, value) {

        state.sort = value;
    },
};

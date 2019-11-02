export default {

    getProducts(state, getters) {

        return _ => state.products;
    },
    getProduct(state, getters) {

        return id => {

            try {

                return getters.getProducts()._embedded.items.find(item => item.id === id);

            } catch (e) {}
        }
    },
    getSorting(state, getters) {
        return _ => state.sort;
    },
};

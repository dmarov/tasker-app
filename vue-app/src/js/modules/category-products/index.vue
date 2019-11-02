<script>
import { mapGetters, mapActions } from 'vuex';
import Noty from 'noty';
import Product from './item';
import Pagination from './pagination';
import Sorting from './sorting';
import ProductCreator from './creator';

export default {

    props: [
        'id',
    ],
    computed: {
        ...mapGetters([
            'getParam'
        ]),
        ...mapGetters('category-products', [
            'getProducts'
        ]),
        products() {

            return this.getProducts();
        },
        lastPage() {

            return this.products.page.totalPages;
        },
    },
    components: {
        'app-product': Product,
        'pagination': Pagination,
        'sorting': Sorting,
        'product-creator': ProductCreator,
    },
    methods: {
        ...mapActions('category-products', [
            'setProductsPage',
        ]),
        async setPage({ cid, page }) {

            this.setProductsPage({ cid, page });
        },
    },
    created() {

        let page = this.$route.params.page ? this.$route.params.page : 1;
        let id = this.$route.params.id;
        this.setPage({ cid: id, page });
    },
    watch: {
        '$route' (to, from) {

            if (to.params.page !== from.params.page || to.params.id !== from.params.id)
                this.setPage({ cid: to.params.id, page: to.params.page});
        },
    },
}

</script>

<template lang='pug'>

    .products-content
        product-creator
        sorting
        .pagination-wrapper(v-if="products")
            pagination(:radius="2" :last-page="lastPage")
        .products(v-if="products")
            app-product(v-for="product in products._embedded.items" :key="product.id" :id="product.id")
        .pagination-wrapper(v-if="products")
            pagination(:radius="2" :last-page="lastPage")
</template>

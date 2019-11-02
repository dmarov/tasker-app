<script>
import { mapGetters, mapActions } from 'vuex';
import Noty from 'noty';
import Product from './item';
import Pagination from './pagination';
import Sorting from './sorting';

export default {

    computed: {
        ...mapGetters([
            'getParam'
        ]),
        ...mapGetters('products', [
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
    },
    methods: {
        ...mapActions('products', [
            'setProductsPage',
        ]),
        async setPage(page) {

            this.setProductsPage(page);
        },
    },
    created() {

        let page = this.$route.params.page ? this.$route.params.page : 1;
        this.setPage(page);
    },
    watch: {
        '$route' (to, from) {

            if (to.params.page !== from.params.page)
                this.setPage(to.params.page);
        },
    },
}

</script>

<template lang='pug'>

    .products-content
        sorting
        .pagination-wrapper(v-if="products")
            pagination(:radius="2" :last-page="lastPage")
        .products(v-if="products")
            app-product(v-for="product in products._embedded.items" :key="product.id" :id="product.id")
        .pagination-wrapper(v-if="products")
            pagination(:radius="2" :last-page="lastPage")
</template>

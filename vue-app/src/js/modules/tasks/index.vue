<script>
import { mapGetters, mapActions } from 'vuex';
import Noty from 'noty';
import Category from './item';
import NewCategory from './creator';
import Pagination from './pagination';
import Sorting from './sorting';

export default {

    computed: {
        ...mapGetters([
            'getParam'
        ]),
        ...mapGetters('categories', [
            'getCategories'
        ]),
        categories() {

            return this.getCategories();
        },
        lastPage() {

            return this.categories.page.totalPages;
        },
    },
    components: {
        'app-category': Category,
        'new-category': NewCategory,
        'pagination': Pagination,
        'sorting': Sorting,
    },
    methods: {
        ...mapActions('categories', [
            'setCategoriesPage',
        ]),
        async setPage(page) {

            this.setCategoriesPage(page);
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

    .categories-content
        new-category
        sorting
        .pagination-wrapper(v-if="categories")
            pagination(:radius="2" :last-page="lastPage")
        .categories(v-if="categories")
            app-category(v-for="category in categories._embedded.items" :key="category.id" :id="category.id")
        .pagination-wrapper(v-if="categories")
            pagination(:radius="2" :last-page="lastPage")
</template>

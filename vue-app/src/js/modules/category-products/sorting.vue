<script>

import { mapGetters, mapActions } from 'vuex';

export default {

    data: _ => {
        return {
            categoryId: undefined,
        }
    },
    computed: {
        ...mapGetters('category-products', [
            'getSorting'
        ]),
        sort: {
            get() {
                return this.getSorting()[0];
            },
            set(value) {
                this.setSorting([value]);
                this.refreshProducts({ cid: this.categoryId });
            },
        },
    },
    methods: {
        ...mapActions('category-products', [
            'setSorting',
            'refreshProducts',
        ]),
    },
    watch: {
        '$route' (to, from) {

            if (to.params.id !== from.params.id)
                this.categoryId = parseInt(to.params.id);
        },
    },
    created() {

        let cid = this.$route.params.id;

        if (cid !== undefined)
            this.categoryId = parseInt(cid);
    },
};

</script>
<template lang="pug">
    .sorting
        select(v-model='sort')
            option(:value="'title,asc'") Title ascending
            option(:value="'title,desc'") Title descending
            option(:value="'price,asc'") Price ascending
            option(:value="'price,desc'") Price descending
            option(:value="'count,asc'") Count ascending
            option(:value="'count,desc'") Count descending
</template>

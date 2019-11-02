<script>

import { mapGetters, mapActions } from 'vuex';

export default {

    computed: {
        ...mapGetters('products', [
            'getSorting'
        ]),
        sort: {
            get() {
                return this.getSorting()[0];
            },
            set(value) {
                this.setSorting([value]);
                this.refreshProducts();
            },
        },
    },
    methods: {
        ...mapActions('products', [
            'setSorting',
            'refreshProducts',
        ]),
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

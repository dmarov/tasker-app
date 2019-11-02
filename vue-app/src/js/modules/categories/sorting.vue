<script>

import { mapGetters, mapActions } from 'vuex';

export default {

    computed: {
        ...mapGetters('categories', [
            'getSorting'
        ]),
        sort: {
            get() {
                return this.getSorting()[0];
            },
            set(value) {
                this.setSorting([value]);
                this.refreshCategories();
            },
        },
    },
    methods: {
        ...mapActions('categories', [
            'setSorting',
            'refreshCategories',
        ]),
    },
};

</script>
<template lang="pug">
    .sorting
        select(v-model='sort')
            option(:value="'title,asc'") Title ascending
            option(:value="'title,desc'") Title descending
</template>

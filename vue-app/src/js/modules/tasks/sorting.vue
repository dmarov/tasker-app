<script>

import { mapGetters, mapActions } from 'vuex';

export default {

    computed: {
        ...mapGetters('tasks', [
            'getSorting'
        ]),
        sort: {
            get() {
                return this.getSorting()[0];
            },
            set(value) {
                this.setSorting([value]);
                this.refreshTasks();
            },
        },
    },
    methods: {
        ...mapActions('tasks', [
            'setSorting',
            'refreshTasks',
        ]),
    },
};

</script>
<template lang="pug">
    .sorting
        select(v-model='sort')
            option(:value="'username,asc'") Username ascending
            option(:value="'username,desc'") Username descending
            option(:value="'email,asc'") Email ascending
            option(:value="'email,desc'") Email descending
            option(:value="'status,asc'") Status ascending
            option(:value="'status,desc'") Status descending
</template>

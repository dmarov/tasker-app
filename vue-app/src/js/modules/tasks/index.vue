<script>
import { mapGetters, mapActions } from 'vuex';
import Noty from 'noty';
import Task from './item';
import NewTask from './creator';
import Pagination from './pagination';
import Sorting from './sorting';

export default {

    computed: {
        ...mapGetters([
            'getParam'
        ]),
        ...mapGetters('tasks', [
            'getTasks'
        ]),
        tasks() {

            return this.getTasks();
        },
        lastPage() {

            return Math.ceil(this.tasks.total / this.tasks.limit);
        },
    },
    components: {
        'app-task': Task,
        'new-task': NewTask,
        'pagination': Pagination,
        'sorting': Sorting,
    },
    methods: {
        ...mapActions('tasks', [
            'setTasksPage',
        ]),
        async setPage(page) {

            this.setTasksPage(page);
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

    .tasks-content
        new-task
        sorting
        .pagination-wrapper(v-if="tasks")
            pagination(:radius="2" :last-page="lastPage")
        .tasks(v-if="tasks")
            app-task(v-for="task in tasks._embedded.items" :key="task.id" :id="task.id")
        .pagination-wrapper(v-if="tasks")
            pagination(:radius="2" :last-page="lastPage")
</template>

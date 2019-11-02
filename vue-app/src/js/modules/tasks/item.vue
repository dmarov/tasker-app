<script>
import { mapGetters, mapActions } from 'vuex';

export default {

    props: [
        'id',
    ],
    data: _ => {
        return  {
            editable: false,
        };
    },
    computed: {
        ...mapGetters('tasks', [
            'getTask'
        ]),
        task() {
            return this.getTask(this.id);
        },
        username: {
            get() {
                return this.task.username;
            },
            set(value) {

                this.patchTask({
                    id: this.id,
                    patch: [{ op: 'add', path: "/username", value }],
                });
            },
        },
        email: {
            get() {
                return this.task.email;
            },
            set(value) {

                this.patchTask({
                    id: this.id,
                    patch: [{ op: 'add', path: "/email", value }],
                });
            },
        },
        text: {
            get() {
                return this.task.text;
            },
            set(value) {

                this.patchTask({
                    id: this.id,
                    patch: [{ op: 'add', path: "/text", value }],
                });
            },
        },
    },
    methods: {
        ...mapActions('tasks', [
            'patchTask',
            'deleteTask',
        ]),
        toggleEdit() {

            this.editable = !this.editable;
        },
        delete() {

            this.deleteTask(this.id);
        },
    },
}

</script>

<template lang='pug'>

    .task
        input.task__username(v-model="username" :disabled="!editable")
        input.task__email(v-model="email" :disabled="!editable")
        textarea.task__text(v-model="text" :disabled="!editable")
        button.task__button.task__button_delete(@click='toggleEdit()') {{ editable == true ? 'finish editing' : 'edit' }}

</template>

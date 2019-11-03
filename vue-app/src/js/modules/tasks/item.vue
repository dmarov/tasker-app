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
        ...mapGetters([
            'getUserName'
        ]),
        task() {
            return this.getTask(this.id);
        },
        loginUser() {
            return this.getUserName();
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
        finished: {
            get() {
                return this.task.status === "finished";
            },
            set(value) {

                if (value) {

                    this.patchTask({
                        id: this.id,
                        patch: [{ op: 'add', path: "/status", value: "finished" }],
                    });
                } else {

                    this.patchTask({
                        id: this.id,
                        patch: [{ op: 'add', path: "/status", value: "in progress" }],
                    });
                }
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
        .task__tooltip(v-if="(task.edited || finished) && !editable")
            div(v-if="task.edited") Edited by admin
            div(v-if="finished") Finished
        input.task__username.text-input(v-model.lazy="username" :disabled="!editable")
        input.task__email.text-input(v-model.lazy="email" :disabled="!editable")
        textarea.task__text.text-input(v-model.lazy="text" :disabled="!editable")
        input(type="checkbox" v-model="finished" :disabled="!loginUser" v-if="loginUser")
        button.button.task__button.task__button_delete(@click='toggleEdit()' v-if="loginUser") {{ editable == true ? 'finish editing' : 'edit' }}

</template>

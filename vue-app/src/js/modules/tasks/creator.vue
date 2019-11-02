<script>

import { mapActions } from 'vuex';

export default {

    data: _ => {

        return {
            username: '',
            email: '',
            text: '',
        };
    },
    methods: {
        ...mapActions('tasks', [
            'appendTask',
        ]),
        append() {

            this.appendTask({
                username: this.username,
                email: this.email,
                text: this.text,
            });
        },
    },
};

</script>
<template lang="pug">
    .new-task
        input.text-input.new-task__username(v-model="username" placeholder="username")
        input.text-input.new-task__email(v-model="email" placeholder="email")
        textarea.text-input.new-task__text(v-model="text" placeholder="text")
        button.button.new-task__button(@click="append()") Create
</template>

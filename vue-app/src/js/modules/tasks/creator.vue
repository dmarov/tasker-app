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
        input.text-input.new-task__username(v-model="username")
        input.text-input.new-task__email(v-model="email")
        textarea.text-input.new-task__text(v-model="text")
        button.button.new-task__button(@click="append()") Create
</template>

<script>

import { mapActions } from 'vuex';

export default {

    data: _ => {

        return {
            title: '',
            description: '',
        };
    },
    methods: {
        ...mapActions('categories', [
            'appendCategory',
        ]),
        append() {

            this.appendCategory({
                title: this.title,
                description: this.description,
            });
        },
    },
};

</script>
<template lang="pug">
    .new-category
        input.new-category__title(v-model="title")
        textarea.new-category__description(v-model="description")
        button.new-category__button(@click="append()") Create
</template>

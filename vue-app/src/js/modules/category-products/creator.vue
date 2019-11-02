<script>

import { mapActions } from 'vuex';

export default {

    data: _ => {

        return {
            title: '',
            categoryId: undefined,
            description: '',
            price: 0,
            count: 0,
            imageUrl: "",
        };
    },
    methods: {
        ...mapActions('category-products', [
            'appendProduct',
        ]),
        append() {

            this.appendProduct({
                title: this.title,
                categoryId: this.categoryId,
                description: this.description,
                price: parseFloat(this.price),
                count: parseInt(this.count),
                imageUrl: this.imageUrl,
            });
        },
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
    .new-product
        input.new-product__title(v-model="title")
        textarea.new-product__description(v-model="description")
        input.new-product__price(v-model="price")
        input.new-product__count(v-model="count")
        input.new-product__image-url(v-model="imageUrl")
        button.new-product__button(@click="append()") Create
</template>

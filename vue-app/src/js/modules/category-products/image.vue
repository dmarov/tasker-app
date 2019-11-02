<script>
import { mapGetters, mapActions } from 'vuex';

export default {

    props: [
        'id',
    ],
    computed: {
        ...mapGetters('category-products', [
            'getProduct'
        ]),
        product() {
            return this.getProduct(this.id);
        },
        imageUrl: {

            get() {
                return this.product.imageUrl;
            },
            set(value) {

                this.patchProduct({
                    id: this.id,
                    patch: [{ op: 'add', path: "/imageUrl", value }],
                });
            },
        },
        imageStyle() {

            return {
                "background-image": `url(${encodeURI(this.imageUrl)})`
            };
        },
    },
    methods: {
        ...mapActions('category-products', [
            'patchProduct',
        ]),
    },
}

</script>

<template lang='pug'>

    .product-image
        input.product-image__url(v-model="imageUrl")
        .product-image__picture(:style="imageStyle")

</template>

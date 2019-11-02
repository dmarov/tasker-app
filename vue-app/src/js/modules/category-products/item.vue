<script>
import { mapGetters, mapActions } from 'vuex';

import ProductImage from "./image";

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
        ...mapGetters('category-products', [
            'getProduct'
        ]),
        product() {
            return this.getProduct(this.id);
        },
        title: {
            get() {
                return this.product.title;
            },
            set(value) {

                this.patchProduct({
                    id: this.id,
                    patch: [{ op: 'add', path: "/title", value }],
                });
            },
        },
        description: {
            get() {
                return this.product.description;
            },
            set(value) {

                this.patchProduct({
                    id: this.id,
                    patch: [{ op: 'add', path: "/description", value }],
                });
            },
        },
        price: {
            get() {
                return this.product.price;
            },
            set(value) {

                value = parseFloat(value);

                this.patchProduct({
                    id: this.id,
                    patch: [{ op: 'add', path: "/price", value }],
                });
            },
        },
        count: {
            get() {
                return this.product.count;
            },
            set(value) {

                value = parseInt(value);

                this.patchProduct({
                    id: this.id,
                    patch: [{ op: 'add', path: "/count", value }],
                });
            },
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
            'deleteProduct',
        ]),
        toggleEdit() {

            this.editable = !this.editable;
        },
        openDeletePopup() {

            this.$modal.show('dialog', {
                title: 'delete product',
                text: 'Are you sure about that?',
                buttons: [
                    { title: "Yes", handler: _ => { this.delete(); this.$modal.hide('dialog'); }},
                    { title: "No", handler: _ => { this.$modal.hide('dialog'); }},
                ],
            });
        },
        openImagePopup() {
            this.$modal.show(ProductImage, {
                id: this.id
            }, {
                width: "80%",
                height: "80%",
            });
        },
        delete() {

            this.deleteProduct(this.id);
        },
    },
}

</script>

<template lang='pug'>

    .product
        input.product__title(v-model="title" :disabled="!editable")
        textarea.product__description(v-model="description" :disabled="!editable")
        input.product__price(v-model="price" :disabled="!editable")
        input.product__count(v-model="count" :disabled="!editable")
        .product__image.container__el_pointable(:style="imageStyle" @click="openImagePopup()") {{ imageUrl ? "" : "no image" }}
        button.product__button.product__button_delete(@click='toggleEdit()') {{ editable == true ? 'finish editing' : 'edit' }}
        button.product__button.product__button_delete(@click='openDeletePopup()') Delete

</template>

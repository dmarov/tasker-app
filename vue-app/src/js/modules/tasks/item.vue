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
        ...mapGetters('categories', [
            'getCategory'
        ]),
        category() {
            return this.getCategory(this.id);
        },
        title: {
            get() {
                return this.category.title;
            },
            set(value) {

                this.patchCategory({
                    id: this.id,
                    patch: [{ op: 'add', path: "/title", value }],
                });
            },
        },
        description: {
            get() {
                return this.category.description;
            },
            set(value) {

                this.patchCategory({
                    id: this.id,
                    patch: [{ op: 'add', path: "/description", value }],
                });
            },
        },
    },
    methods: {
        ...mapActions('categories', [
            'patchCategory',
            'deleteCategory',
        ]),
        ...mapActions('category-products', [
            'initCategory',
        ]),
        toggleEdit() {

            this.editable = !this.editable;
        },
        openDeletePopup() {

            this.$modal.show('dialog', {
                title: 'delete category',
                text: 'Are you sure about that?',
                buttons: [
                    { title: "Yes", handler: _ => { this.delete(); this.$modal.hide('dialog'); }},
                    { title: "No", handler: _ => { this.$modal.hide('dialog'); }},
                ],
            });
        },
        delete() {

            this.deleteCategory(this.id);
        },
    },
}

</script>

<template lang='pug'>

    .category
        input.category__title(v-model="title" :disabled="!editable")
        textarea.category__description(v-model="description" :disabled="!editable")
        button.category__button.category__button_delete(@click='toggleEdit()') {{ editable == true ? 'finish editing' : 'edit' }}
        button.category__button.category__button_delete(@click='openDeletePopup()') Delete
        button.category__button(@click="$router.push(`/categories/${id}/products`)") Show products

</template>

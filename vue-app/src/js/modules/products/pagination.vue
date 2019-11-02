<script>
export default {
    props: [ 'last-page', 'radius' ],
    data: _ => {
        return {
            current: 1,
        };
    },
    computed: {
        last() {
            return this.lastPage;
        },
        range() {
            let from = Math.max(1, this.current - this.radius);
            let to = Math.min(this.last, this.current + this.radius);
            if (from == 1) ++from;
            if (to == this.last) --to;
            if (from > to) return [];
            if (from == 3) --from;
            if (to == this.last - 2) ++to;
            let length = to - from + 1;
            let res = Array.from({length: length}, (v, k) => {
                return k + from;
            });
            return res;
        },
    },
    methods: {
        getClass(n) {
            if (n == this.current)
                return { pagination__item_active: true };
            else
                return {};
        },
        setCurrent(page) {
            this.current = page;
        },
    },
    watch: {
        '$route' (to, from) {
            if (to.params.page !== from.params.page)
                this.current = parseInt(to.params.page);
        },
    },
    created() {

        let page = this.$route.params.page;
        if (page ===  undefined) page = 1;
        this.setCurrent(parseInt(page));
    },
};
</script>
<template lang='pug'>
    .pagination
        .pagination__item(@click='$router.push(`/products/${Math.max(1, current - 1)}`)')
            .pagination__link.pagination__link_prev
        .pagination__item(@click='$router.push(`/products/1`)' :class='getClass(1)')
            .pagination__link 1
        .pagination__item(v-if='(current - radius > 1 + 2)')
            .pagination__dots ...
        .pagination__item(v-for='index in range' @click='$router.push(`/products/${index}`)' :class='getClass(index)')
            .pagination__link {{ index }}
        .pagination__item(v-if='(current + radius < last - 2)')
            .pagination__dots ...
        .pagination__item(@click='$router.push(`/products/${last}`)' :class='getClass(last)' v-if='last > 1')
            .pagination__link {{ last }}
        .pagination__item(@click='$router.push(`/products/${Math.min(last, current + 1)}`)')
            .pagination__link.pagination__link_next
</template>

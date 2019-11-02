<script>

export default {

};

</script>
<template lang='pug'>

    nav.menu
        ul.menu__items
            li.menu__item
                router-link.container__el_pointable.menu__link.container__el_nolink(to="/categories" exact-active-class="menu__link_active") Categories
            li.menu__item
                router-link.container__el_pointable.menu__link.container__el_nolink(to="/products" exact-active-class="menu__link_active") Products

</template>

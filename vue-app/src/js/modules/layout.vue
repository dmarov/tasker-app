<script>

import Menu from '../components/menu';
import Toolbar from '../components/toolbar';

export default {

    components: {
        'app-menu': Menu,
        'app-toolbar': Toolbar,
    },
};

</script>
<template lang="pug">

    .container
        app-toolbar
        app-menu
        .content
            router-view
        v-dialog
</template>

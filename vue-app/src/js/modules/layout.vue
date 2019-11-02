<script>

import Toolbar from '../components/toolbar';

export default {

    components: {
        'app-toolbar': Toolbar,
    },
};

</script>
<template lang="pug">

    .container
        app-toolbar
        .content
            router-view
        v-dialog
</template>

<script>

import LoginPopup from './login-popup';

export default {

    methods: {

        openLoginPopup() {

            this.$modal.show(LoginPopup, {}, { draggable: true });
        },
        logOut() {

            sessionStorage.removeItem('token');
            location.reload();
        },
    }
}

</script>
<template lang='pug'>

    .toolbar
        button.button.toolbar__button(@click='openLoginPopup()') Login
        button.button.toolbar__button(@click='logOut()') Logout

</template>

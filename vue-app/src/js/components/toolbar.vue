<script>

import LoginPopup from './login-popup';
import { mapGetters } from 'vuex';

export default {

    methods: {

        ...mapGetters([
            'getUserName',
        ]),
        openLoginPopup() {

            this.$modal.show(LoginPopup, {}, { draggable: true });
        },
        logOut() {

            sessionStorage.removeItem('token');
            location.reload();
        },
    },
    computed: {
        username() {

            return this.getUserName()();
        },
    }
}

</script>
<template lang='pug'>

    .toolbar
        .toolbar__label(v-if="username") Logged in as {{ username }}
        button.button.toolbar__button(@click='openLoginPopup()') Login
        button.button.toolbar__button(@click='logOut()') Logout

</template>

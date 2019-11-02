<script>
import { mapGetters, mapActions } from 'vuex';
import authfetch from '../lib/authfetch.js';
import Noty from 'noty';

export default {

    name: 'auth',
    data: _ => {
        return {
            username: undefined,
            password: undefined,
        };
    },
    computed: {
        ...mapGetters([
            'getUserName',
            'getUserPassword',
        ]),
    },
    methods: {
        ...mapActions([
            'setAuthToken',
        ]),
        async setUser() {

            let token = btoa(this.username + ':' + this.password);
            this.setAuthToken(token);

            try {
                let response = await authfetch("/api/check-admin");

                this.$emit('close');
                location.reload();
            } catch (e) {

                new Noty({
                    text: "invalid credentials",
                    type: "error"
                }).show();
            }
        },
    },
    created() {

        this.username = this.getUserName();
        this.password = this.getUserPassword();
    },
}

</script>
<template lang='pug'>

    .login-popup
        input.login-popup__field(placeholder="username" v-model="username")
        input.login-popup__field(placeholder="password" type="password" v-model="password")
        button.button.login-popup__field(@click='setUser()') Login

</template>

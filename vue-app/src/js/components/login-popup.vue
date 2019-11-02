<script>
import { mapGetters, mapActions } from 'vuex';

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
        setUser() {

            let token = btoa(this.username + ':' + this.password);
            this.setAuthToken(token);

            this.$emit('close');
            location.reload();
        }
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

export default {

    init(state, params) {

        if (!state.initialized) {

            state.initialized = true;
            state.params = params;
            state.authToken = sessionStorage.getItem('token');
        }
    },
    setAuthToken(state, token) {

        state.authToken = token;
        sessionStorage.setItem('token', token);
    },
};

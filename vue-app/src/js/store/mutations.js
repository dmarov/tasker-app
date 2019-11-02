export default {

    init(state, params) {

        if (!state.initialized) {

            state.initialized = true;
            state.params = params;
            state.authToken = localStorage.getItem('token');
        }
    },
    setAuthToken(state, token) {

        state.authToken = token;
        localStorage.setItem('token', token);
    },
};

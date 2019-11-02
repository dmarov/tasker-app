export default {

    init(context, params) {

        context.commit('init', params);
    },
    setAuthToken(context, token) {

        context.commit('setAuthToken', token);
    },
};
